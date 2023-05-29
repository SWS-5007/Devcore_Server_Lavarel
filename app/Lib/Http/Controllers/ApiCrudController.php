<?php
namespace App\Lib\Http\Controllers;

use App\Lib\Filters\GenericFilter;
use App\Lib\Http\Resources\GenericResourceCollection;
use App\Lib\Http\Resources\GenericResource;
use App\Lib\Http\Requests\ApiRequest;
use App\Lib\Http\Responses\ApiResponse;
use App\Services\PermissionsService;

abstract class ApiCrudController extends ApiBaseController
{
    protected $moduleName = 'base';
    protected $modelName = 'none';
    protected $guard = 'api';
    protected $enabledActions = [
        'list', 'show', 'edit', 'delete', 'create'
    ];

    protected $protectedActions = '*';

    //parametros por defecto
    protected $defaults = [
        'page_size' => 20,
        'order' => '-id',
        'page' => 1,
        'filter' => [],
        'show' => 'active',
    ];
    //nombre de los parámteros
    protected $paramNames = [
        'page_size' => 'count',
        'order' => 'ord',
        'page' => 'page',
        'filter' => 'f',
        'show' => 'show',
    ];
    //opciones de paginacion
    protected $pageSizeOptions = ['10', '20', '50', '100'];
    //incluir en las consultas
    protected $defaultWith = [];
    protected $serviceInstance = null;
    protected $currentOption;

    protected $useSoftDeletes = false;
    protected $useVersions = false;
    protected $hasEnabledFilter = false;
    protected $enabledField = 'enabled';

    /**
     * 
     * @param type $moduleName
     * @param type $modelName
     * @param type $useSoftDeletes
     * @param type $enabledActions
     */
    public function __construct($moduleName, $modelName)
    {
        parent::__construct();
        $this->serviceInstance = null;
        $this->modelName = $modelName;
        $this->moduleName = $moduleName;
        $this->registerNeedleOperations();
    }

    protected function constructOperationCompleteName($operation)
    {
        return $this->moduleName . '/' . $this->modelName . '/' . $operation;
    }


    protected function getNeedleOperations()
    {
        $operations = array();

        foreach ($this->enabledActions as $option) {
            if ($this->isProtectedAction($option)) {
                $needle = $this->constructOperationCompleteName($option);
                $operations[] = $needle;
            }
        }
        if ($this->useSoftDeletes) {
            foreach (['view_trashed', 'restore_trashed', 'delete_permanently'] as $option) {
                if ($this->isProtectedAction($option)) {
                    $needle = $this->constructOperationCompleteName($option);
                    $operations[] = $needle;
                }
            }
        }

        if ($this->useVersions) {
            foreach (['view_versions', 'restore_version'] as $option) {
                if ($this->isProtectedAction($option)) {
                    $needle = $this->constructOperationCompleteName($option);
                    $operations[] = $needle;
                }
            }
        }

        if ($this->hasEnabledFilter) {
            $operations[] = $this->constructOperationCompleteName('view_disabled');
            $operations[] = $this->constructOperationCompleteName('restore_disabled');
        }

        $operations[] = $this->constructOperationCompleteName('view_private_data');
        $operations[] = $this->constructOperationCompleteName('edit_private_data');

        $protectedOperations = [];

        return $operations;
    }

    protected function registerNeedleOperations()
    {
        if ($this->protectedActions === '*' || (is_array($this->protectedActions) && count($this->protectedActions))) {

            PermissionsService::instance()->registerOperations($this->getNeedleOperations(), $this->guard);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected abstract function getModelClass();

    /**
     * @return \Base\Services\GenericService
     */
    protected abstract function createServiceInstance();

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getNewModelInstance()
    {
        $modelClassName = $this->getModelClass();
        return new $modelClassName();
    }

    /**
     * @return \Modules\Base\Services\IGenericService
     */
    protected function getServiceInstance()
    {
        if ($this->serviceInstance == null) {
            $this->serviceInstance = $this->createServiceInstance();
        }
        $this->serviceInstance->setUser($this->getUser());
        return $this->serviceInstance;
    }

    protected function getFilterValues(Request $request, $filterName = 'f')
    {
        $ret = array();
        if ($request->get($filterName)) {
            $filter = $request->get($filterName);
            $filterStr = base64_decode($filter);
            $json = json_decode($filterStr, true);
            if (isset($json['f'])) {
                foreach ($json['f'] as $key => $value) {
                    $ret[$value['k']] = $value;
                }
            }
        }
        return $ret;
    }

    protected function parseFilter($filter)
    {
        $ret = array();
        if (count($filter)) {

            $filterStr = base64_decode($filter);
            $json = json_decode($filterStr, true);
            if (isset($json['f'])) {
                foreach ($json['f'] as $key => $value) {
                    $ret[$value['k']] = $value;
                }
            }
        }
        return $ret;
    }

    protected function validateFilterValue($filterValue)
    {
        $key = $filterValue['k'];
        $field = $filterValue['f'];
        $comparator = $filterValue['c'];
        $value = $filterValue['v'];
        if ($field == '' || $value == '' || $comparator == '') {
            return false;
        }
        return true;
    }

    protected function parseFormData(ApiRequest $request, ApiResponse $response)
    {
        $request->sanitize();
        return $request->all();
    }

    protected function getMessage($option, $data = [])
    {
        $message = '';
        switch ($option) {
            case 'creation_success':
            case 'invalid_data':
            case 'update_success':
            case 'delete_success':
                if (trans()->has('messages.' . $this->moduleName . '.' . $option)) {
                    $message = trans('messages.' . $this->moduleName . '.' . $option);
                } else {
                    $message = trans('messages.' . $option);
                }
                break;
        }
        return $message;
    }

    public function getRequiredFiltersValues()
    {
        return array();
    }

    public function validateFilter(Request $request, $filterName = 'filter')
    {
        $filterValues = $this->getFilterValues($request, $filterName);
        $requiredFilter = $this->getRequiredFiltersValues();

        $ret = array();

        if (count($requiredFilter)) {
            foreach ($requiredFilter as $key => $reqFilterRow) {
                $requiredFilter[$key]['valid'] = 0;
                foreach ($filterValues as $f) {

                    $field = $f['f'];
                    if ($field == $reqFilterRow['field'] && $this->validateFilterValue($f)) {
                        $requiredFilter[$key]['valid'] = 1;
                    }
                }
                $ret[$key] = $requiredFilter[$key];
            }
        }
        return $ret;
    }

    public function filterIsValid(Request $request, $filterName = 'filter')
    {
        $errors = array();
        $requiredFilter = $this->validateFilter($request, $filterName);
        foreach ($requiredFilter as $key => $filterName) {
            //echo $requiredFilter[$key]['valid'];
            if (!$requiredFilter[$key]['valid']) {
                if (isset($requiredFilter[$key]['message'])) {
                    $errors[] = $requiredFilter[$key]['message'];
                } else {
                    $errors[] = "El filtro " . $requiredFilter[$key]['field'] . ' no es válido.';
                }
            }
        }

        return $errors;
    }

    /**
     * 
     * @param App\Lib\Http\Requests\ApiRequest $request
     * @param type $option
     * @param type $config
     */
    public function getFilterInstance(ApiRequest $request, $option, $config = [])
    {
        return GenericFilter::for($this->getModelClass(), $request);
    }

    /**
     * 
     * @param App\Lib\Http\Requests\ApiRequest $request
     * @param App\Lib\Http\Responses\ApiResponse $response
     * @param type $data
     * @param type $option
     * @param type $config
     * @return Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function prepareListResourceToSend(ApiRequest $request, ApiResponse $response, $data, $option, $config = array())
    {
        return new GenericResourceCollection($data, $this->getUser());
    }

    /**
     * 
     * @param App\Lib\Http\Requests\ApiRequest $request
     * @param App\Lib\Http\Responses\ApiResponse $response
     * @param type $data
     * @param type $option
     * @param type $config
     * @return Illuminate\Http\Resources\Json\Resource
     */
    public function prepareSingleResourceToSend(ApiRequest $request, ApiResponse $response, $data, $option, $config = array())
    {
        return new GenericResource($data, $this->getUser());
    }

    //
    //
    //
    // <editor-fold defaultstate="collapsed" desc="GETTERS Y SETTERS">
    //
    //
    function getModuleName()
    {
        return $this->moduleName;
    }

    function getModelName()
    {
        return $this->modelName;
    }


    function getGuard()
    {
        return $this->guard;
    }

    function getenabledActions()
    {
        return $this->enabledActions;
    }

    function getDefaults()
    {
        return $this->defaults;
    }

    function getParamNames()
    {
        return $this->paramNames;
    }

    function getPageSizeOptions()
    {
        return $this->pageSizeOptions;
    }

    function getDefaultWith()
    {
        return $this->defaultWith;
    }

    function getRedirects()
    {
        return $this->redirects;
    }

    function getCurrentOption()
    {
        return $this->currentOption;
    }

    function getUseSoftDeletes()
    {
        return $this->useSoftDeletes;
    }

    function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    function setGuard($guard)
    {
        $this->guard = $guard;
    }

    function setenabledActions($enabledActions)
    {
        $this->enabledActions = $enabledActions;
    }

    function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }

    function setParamNames($paramNames)
    {
        $this->paramNames = $paramNames;
    }

    function setPageSizeOptions($pageSizeOptions)
    {
        $this->pageSizeOptions = $pageSizeOptions;
    }

    function setDefaultWith($defaultWith)
    {
        $this->defaultWith = $defaultWith;
    }

    function setRedirects($redirects)
    {
        $this->redirects = $redirects;
    }

    function setCurrentOption($currentOption)
    {
        $this->currentOption = $currentOption;
    }

    function setUseSoftDeletes($useSoftDeletes)
    {
        $this->useSoftDeletes = $useSoftDeletes;
    }



    //
    //
    //
    // </editor-fold>
    //
    //
    //

    //
    //
    // <editor-fold defaultstate="collapsed" desc="PERMISSION CHECKER">

    /**
     * 
     * @return type
     */
    protected function currentOptionEnabled()
    {
        return $this->optionEnabled($this->currentOption);
    }

    /**
     * Get if option is enabled
     * @param string $option
     * @return boolean 
     */
    protected function optionEnabled($option)
    {
        return in_array($option, $this->enabledActions);
    }

    /**
     * Check if current option is enabled
     */
    protected function checKIfCurrentOptionEnabled()
    {
        $this->checkIfOptionEnabled($this->currentOption);
    }

    /**
     * Check if current option is enabled, else throw 404
     * @param string $option
     */
    protected function checkIfOptionEnabled($option)
    {
        if (!$this->optionEnabled($option)) {
            abort(404);
        }
    }

    protected function isProtectedAction($option)
    {
        if ($this->protectedActions === '*') {
            return true;
        }


        if (is_array($this->protectedActions) && in_array($option, array_values($this->protectedActions))) {
            return true;
        }

        return false;
    }

    /**
     * Chequea si el usuario actual puede realizar una acción
     * @param string $action
     * @return boolean
     */
    public function can($action)
    {

        if (!$this->getUser()) {
            return false;
        }

        if ($this->getUser() && $this->getUser()->is_super_admin) {
            return true;
        }
        return $this->getUser()->can($this->moduleName . '/' . $this->modelName . '/' . $action);
    }

    /**
     * Chequea si el usaurio atual puede crear
     * @return boolean
     */
    public function canCreate()
    {
        return $this->can('create');
    }

    public function canViewTrashed()
    {
        return $this->can('view_trashed');
    }

    public function canViewDisabled()
    {
        return $this->can('view_disabled');
    }

    public function canRestore()
    {
        return $this->can('restore_trashed');
    }

    public function canRemovePermanently()
    {
        return $this->can('delete_permanently');
    }

    /**
     * Chequea si el usuario actual puede realizar una acción o lanza 403
     * @param string $action
     */
    public function canOrFail($action)
    {
        if (!$this->can($action)) {
            abort(403, trans('unauthorized'));
        }
        return true;
    }

    public function canOrFailCurrentOption()
    {
        if ($this->isProtectedAction($this->currentOption)) {
            $this->canOrFail($this->currentOption);
        }
    }

    /**
     * Hace todos los chequeos necesarios
     */
    public function checkAll()
    {
        $this->checkIfCurrentOptionEnabled();
        $this->canOrFailCurrentOption();
    }

    // </editor-fold>
    // 
    //
    // 
    // 
    // 
    // <editor-fold defaultstate="collapsed" desc="CRUD METHODS">
    protected function getPaginatorParam($name)
    {
        return request($this->paramNames[$name], $this->defaults[$name]);
    }

    protected function getIncludes($action, $config = [])
    {
        return  isset($config['with']) ? $config['with'] : $this->defaultWith;
    }

    protected function setPaginatorParams($paginator)
    {
        //agrego los parámteros actuales al paginador
        $paginator = $paginator->appends([
            $this->paramNames['order'] => $order,
            $this->paramNames['page_size'] => $size,
            $this->paramNames['filter'] => $filter,
            $this->paramNames['show'] => $show,
        ]);
        return $paginator;
    }


    protected function addEnabledFilter($result, ApiRequest $request, ApiResponse $response)
    {
        if ($this->hasEnabledFilter) {
            if (!$this->canViewDisabled()) {
                $result->where([$this->enabledField => true]);
            }
        }
    }

    /**
     * 
     * @param App\Lib\Http\Requests\ApiRequest $request
     * @param App\Lib\Http\Responses\ApiResponse $response
     * @param type $config
     * @return type
     */
    public function index(ApiRequest $request, ApiResponse $response, $config = array())
    {
        return $this->listObjectsByFilterPaginated($request, $response, $config)->render();
    }


    // <editor-fold defaultstate="collapsed" desc="LIST OBJECTS">
    public function listObjectsByFilter(ApiRequest $request, ApiResponse $response, $config = [])
    {
        $this->currentOption = 'list';
        $this->checkAll();
        $show = $this->getPaginatorParam('show');

        $with = $this->getIncludes($this->currentOption, $config);
        $filter = $this->getFilterInstance($request, $this->currentOption, $config);
        $filter->with($with);
        $this->addEnabledFilter($filter, $request, $response);
        $results = $this->getServiceInstance()->listObjectsByFilter($filter);


        if ($show != 'active' && $this->useSoftDeletes && $this->canViewTrashed()) {
            if ($show == 'all') {
                $results = $results->withTrashed();
            } else {
                $results = $results->onlyTrashed();
            }
        }
        $data = [];
        $data['config'] = $config;
        $data['data'] = $this->prepareListResourceToSend($request, $response, $results->get(), $this->currentOption, $config);
        $data['showActiveFilter'] = $this->useSoftDeletes && $this->canViewTrashed();
        $data['show'] = $show;

        $response->setPayload($data['data']);
        return $response;
    }

    public function listObjectsByFilterPaginated(ApiRequest $request, ApiResponse $response, $config = [])
    {
        $this->currentOption = 'list';
        $this->checkAll();
        $show = $this->getPaginatorParam('show');
        $page = $this->getPaginatorParam('page');
        $size = $this->getPaginatorParam('page_size');

        $with = $this->getIncludes($this->currentOption, $config);
        $filter = $this->getFilterInstance($request, $this->currentOption, $config);
        $filter->with($with);

        $this->addEnabledFilter($filter, $request, $response);
        $results = $this->getServiceInstance()->listObjectsByFilterPaginated($filter, $page, $size);
        /*if ($results->currentPage() > 1 && $results->currentPage() > $results->lastPage()) {
            $currentPath = \Route::getFacadeRoot()->current()->getName();
            $request->merge([$this->paramNames['page'] => $results->lastPage()]);
            return redirect(route($currentPath, $request->all()));
        }*/

        if ($show != 'active' && $this->useSoftDeletes && $this->canViewTrashed()) {
            if ($show == 'all') {
                $results = $results->withTrashed();
            } else {
                $results = $results->onlyTrashed();
            }
        }



        $data = [];
        $data['config'] = $config;
        $data['data'] = $this->prepareListResourceToSend($request, $response, $results, $this->currentOption, $config);
        $data['showActiveFilter'] = $this->useSoftDeletes && $this->canViewTrashed();
        $data['show'] = $show;

        $response->setPayload($data['data']);
        return $response;
    }

    public function listObjects(ApiRequest $request, ApiResponse $response, $config = array())
    {
        $this->currentOption = 'list';
        $this->checkAll();
        $order = $this->getPaginatorParam('order');
        $filterStr = $this->getPaginatorParam('filter');
        $filter = $this->parseFilter($filterStr);
        $show = $this->getPaginatorParam('show');
        $filter['show'] = $show;

        $with = $this->getIncludes($this->currentOption, $config);
        unset($filter['show']);
        $this->addEnabledFilter($filter, $request, $response);
        $results = $this->getServiceInstance()->listObjects($with, $filter, $order);


        if ($show != 'active' && $this->useSoftDeletes && $this->canViewTrashed()) {
            if ($show == 'all') {
                $results = $results->withTrashed();
            } else {
                $results = $results->onlyTrashed();
            }
        }

        $data = [];
        $data['config'] = $config;
        $data['data'] = $this->prepareListResourceToSend($request, $response, $results->get(), $this->currentOption, $config);
        $data['showActiveFilter'] = $this->useSoftDeletes && $this->canViewTrashed();
        $data['show'] = $show;
        $data['filter'] = $filter;

        $response->setPayload($data['data']);
        return $response;
    }

    /**
     * 
     * @param App\Lib\Http\Requests\ApiRequest $request
     * @param App\Lib\Http\Responses\ApiResponse $response
     * @param type $config
     * @returnModules\Base\Http\Responses\ApiResponse
     */
    public function listObjectsPaginated(ApiRequest $request, ApiResponse $response, $config = array())
    {
        $this->currentOption = 'list';
        $this->checkAll();
        $order = $this->getPaginatorParam('order');
        $filterStr = $this->getPaginatorParam('filter');
        $filter = $this->parseFilter($filterStr);
        $page = $this->getPaginatorParam('page');
        $size = $this->getPaginatorParam('page_size');
        $show = $this->getPaginatorParam('show');
        $filter['show'] = $show;

        $with = $this->getIncludes($this->currentOption, $config);
        $this->addEnabledFilter($filter, $request, $response);
        $paginator = $this->getServiceInstance()->listObjectsPaginated($with, $filter, $page, $size, $order);
        unset($filter['show']);


        //agrego los parámteros actuales al paginador
        $paginator = $paginator->appends([
            $this->paramNames['order'] => $order,
            $this->paramNames['page_size'] => $size,
            $this->paramNames['filter'] => $filter,
        ]);

        if ($show != 'active' && $this->useSoftDeletes && $this->canViewTrashed()) {
            $paginator = $paginator->appends([
                $this->paramNames['show'] => $show
            ]);
        }

        $data = [];
        $data['config'] = $config;
        $paginatorArr = $paginator->toArray();
        $data['data'] = $this->prepareListResourceToSend($request, $response, $paginator->toArray()['data'], $this->currentOption, $config);
        $data['paginator'] = array_except($paginatorArr, 'data');
        $data['paginator']['pageSizeOptions'] = $this->pageSizeOptions;
        $data['showActiveFilter'] = $this->useSoftDeletes && $this->canViewTrashed();
        $data['show'] = $show;
        $data['filter'] = $filter;

        $response->setPayload($data['data']);
        return $response;
    }

    // </editor-fold>

    public function show(ApiRequest $request, ApiResponse $response, $id, $config = array())
    {
        $this->currentOption = 'show';
        $this->checkAll();
        $with = $this->getIncludes($this->currentOption, $config);
        $object = $this->getServiceInstance()->findByPrimaryKey($id, $this->useSoftDeletes && $this->canViewTrashed(), $with);
        $this->addEnabledFilter($object, $request, $response);
        if (!$object) {
            abort(404);
        }
        $response->setPayload($this->prepareSingleResourceToSend($request, $response, $object, $this->currentOption, $config));
        return $response->render();
    }

    public function store(ApiRequest $request, ApiResponse $response, $config = array(), $data = array())
    {
        $this->currentOption = 'create';
        $this->checkAll();
        try {
            $object = $this->getServiceInstance()->create($this->parseFormData($request, $response));
            $response->setMessageKey($this->modelName . '.creation_success');
            $response->setMessage($this->getMessage('creation_success'));
            //$object = $object->with($this->getIncludes($this->currentOption, $config));
            $response->setPayload($this->prepareSingleResourceToSend($request, $response, $object, $this->currentOption, $config));
        } catch (\Illuminate\Validation\ValidationException $vex) {
            $response->setValidator($vex->validator);
            $response->setSuccess(false);
            $response->setMessageKey('invalid_data');
            $response->setMessage($this->getMessage('invalid_data'));
            $response->setHttpCode(400);
        }

        return $response->render();
    }

    public function update(ApiRequest $request, ApiResponse $response, $id, $config = array(), $data = array())
    {
        $this->currentOption = 'edit';
        $this->checkAll();
        try {
            $object = $this->getServiceInstance()->findByPrimaryKey($id, $this->useSoftDeletes && $this->canViewTrashed());
            if (!$object) {
                abort(404);
            }
            $object = $this->getServiceInstance()->update($object, $this->parseFormData($request, $response));
            $response->setMessageKey('update_success');
            $response->setMessage($this->getMessage('update_success'));
            $response->setPayload($this->prepareSingleResourceToSend($request, $response, $object, $this->currentOption, $config));
        } catch (\Illuminate\Validation\ValidationException $vex) {
            $response->setValidator($vex->validator);
            $response->setSuccess(false);
            $response->setMessageKey('messages.invalid_data');
            $response->setMessage($this->getMessage('messages.invalid_data'));
            $response->setHttpCode(400);
        }

        return $response->render();
    }

    public function destroy(ApiRequest $request, ApiResponse $response, $id, $config = array())
    {
        $this->currentOption = 'delete';
        $this->checkAll();
        $object = $this->getServiceInstance()->findByPrimaryKey($id, $this->useSoftDeletes && $this->canViewTrashed());
        if (!$object) {
            abort(404);
        }
        $deleted = $this->getServiceInstance()->delete($id, ($this->canRemovePermanently() && $request->get('permanent')));
        $response->setSuccess($deleted);
        $response->setMessageKey('delete_success');
        $response->setMessage($this->getMessage('delete_success'));
        if (!$deleted) {
            $response->setMessageKey('messages.unknown_error');
        }
        return $response->render();
    }
}
