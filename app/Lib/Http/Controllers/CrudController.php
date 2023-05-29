<?php

namespace App\Lib\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Lib\Filters\GenericFilter;
use App\Lib\Services\PermissionService;

abstract class CrudController extends BaseController
{

    protected $moduleName = 'base';
    protected $modelName = 'none';
    protected $editParamName = 'id';
    protected $routeBaseName = '';
    protected $viewBaseName = '';
    protected $enabledActions = [
        'list', 'show', 'edit', 'delete', 'create'
    ];
    protected $viewNames = [
        'index' => 'index',
        'view' => 'view',
        'create' => 'create',
        'edit' => 'edit',
    ];
    //parametros por defecto
    protected $defaults = [
        'page_size' => 20,
        'order' => '-id',
        'page' => 1,
        'filter' => array(),
        'show' => 'active',
    ];
    //nombre de los parámteros
    protected $paramNames = [];


    protected $showParamNames = [
        'enabled' => 'active',
        'disabled' => 'trashed',
        'all' => 'all'
    ];

    //opciones de paginacion
    protected $pageSizeOptions = ['10', '20', '50', '100'];
    //incluir en las consultas
    protected $defaultWith = [];
    protected $defaultWithCount = [];
    protected $redirects = [
        'index' => '.home',
        'create' => 'index',
        'edit' => 'index',
        'delete' => 'index'
    ];
    protected $serviceInstance = null;
    protected $backUrl = '';
    protected $currentOption;
    protected $currentEntity;
    protected $useSoftDeletes = false;
    protected $useVersions = false;
    protected $hasEnabledFilter = false;
    protected $protectedActions = '*';
    protected $guard = 'web';

    protected $appId = 'default';
    protected $crudConfigKey = 'default';

    /**
     * 
     * @param string $moduleName El nombre del modulo
     * @param string $routeBaseName Prepend en las rutas
     * @param string $modelName El nombre del modelo
     */
    public function __construct($moduleName, $routeBaseName, $viewBaseName, $editParamName, $modelName, $appId = 'default', $useSoftDeletes = true, $enabledActions = ['list', 'show', 'edit', 'delete', 'create'])
    {
        parent::__construct();
        $this->paramNames = config('filters.params');
        $this->editParamName = $editParamName;
        $this->serviceInstance = null;
        $this->routeBaseName = $routeBaseName;
        $this->viewBaseName = $viewBaseName;
        $this->modelName = $modelName;
        $this->moduleName = $moduleName;
        $this->useSoftDeletes = $useSoftDeletes;
        $this->enabledActions = $enabledActions;
        $this->crudConfigKey = 'crud.' . $appId;
        $this->appId = $appId;
        $this->addBreadCrumb($this->getPageTitle('index'), $this->getRouteToAction('index'));
        $this->registerNeedleOperations();

        $this->backUrl = request()->old('back', request('back', url()->previous($this->getRouteToAction('index'))));
    }

    protected function getPageTitle($page, $params = [])
    {
        return self::getTrans($this->getTransPrefix() . '_' . $page . '.title', $params);
    }

    protected function getTransPrefix()
    {
        return 'generics.pages.' . $this->moduleName;
    }

    protected function isProtectedAction($action)
    {
        return $this->protectedActions === '*' || (is_array($this->protectedActions) && in_array($action, $this->protectedActions));
    }

    protected function constructOperationCompleteName($action)
    {
        return $this->getModuleName() . '::' . $action;
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

            PermissionService::instance()->registerOperations($this->getNeedleOperations(), $this->guard);
        }
    }

    /**
     * 
     */
    protected abstract function getModelClass();

    /**
     * @return IGenericService
     */
    protected abstract function createServiceInstance();

    /**
     * Sobreescribir para agregar elementos a la view dependiendo de la opcion(debe retornar un array)
     * @param type $option
     * @return array
     */
    protected function addDataToView($option, $request, $currentData)
    {
        return [];
    }

    /**
     * Método para parsear los formularios
     * @param string $option
     * @param array $formData
     * @param Request $request
     * @param array $config
     * @param mixed $data
     */
    protected function parseForm($option, $formData, Request $request, $config = array(), $data = array())
    {
        return $formData;
    }

    protected function getNewModelInstance()
    {
        $modelClassName = $this->getModelClass();
        return new $modelClassName();
    }

    /**
     * @return \App\Libs\Crud\IGenericService
     */
    protected function getServiceInstance()
    {
        if ($this->serviceInstance == null) {
            $this->serviceInstance = $this->createServiceInstance();
        }
        return $this->serviceInstance;
    }

    protected function getViewName($action)
    {
        $view = $this->viewBaseName . $this->viewNames[$action];

        if (!View::exists($view)) {
            $view = config($this->crudConfigKey . '.views.' . $this->viewNames[$action]);
        }

        return $view;
    }

    protected function getRouteToAction($action, $params = array(), $abs = false)
    {
        if (strpos($action, '.') === 0) {
            $action = substr($action, 1);
        } else {
            $action = $this->routeBaseName . $action;
        }

        return route($action, $params, $abs);
    }


    protected function getRouteAfterAction($action, $entity = null, $abs = false)
    {
        return $this->backUrl;
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

    function getPrimaryKeyName()
    {
        if (property_exists($this, 'primaryKeyName')) {
            return $this->primaryKeyName;
        }
        return $this->getServiceInstance()->getPrimaryKeyName();
    }

    function getEditParamName()
    {
        return $this->editParamName;
    }

    function getRouteBaseName()
    {
        return $this->routeBaseName;
    }

    function getViewBaseName()
    {
        return $this->viewBaseName;
    }

    function getenabledActions()
    {
        return $this->enabledActions;
    }

    function getViewNames()
    {
        return $this->viewNames;
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

    function getBackUrl()
    {
        return $this->backUrl;
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

    function setEditParamName($editParamName)
    {
        $this->editParamName = $editParamName;
    }

    function setRouteBaseName($routeBaseName)
    {
        $this->routeBaseName = $routeBaseName;
    }

    function setViewBaseName($viewBaseName)
    {
        $this->viewBaseName = $viewBaseName;
    }

    function setenabledActions($enabledActions)
    {
        $this->enabledActions = $enabledActions;
    }

    function setViewNames($viewNames)
    {
        $this->viewNames = $viewNames;
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

    function setBackUrl($backUrl)
    {
        $this->backUrl = $backUrl;
    }

    function setCurrentOption($currentOption)
    {
        $this->currentOption = $currentOption;
    }

    function setUseSoftDeletes($useSoftDeletes)
    {
        $this->useSoftDeletes = $useSoftDeletes;
    }

    function setCurrentEntity($entity)
    {
        $this->currentEntity = $entity;
    }

    function getCurrentEntity()
    {
        return $this->currentEntity;
    }

    //</editor-fold>
    //
    //
    //
    // <editor-fold defaultstate="collapsed" desc="CHEQUEO DE PERMISOS">

    /**
     * 
     * @return type
     */
    protected function currentOptionEnabled()
    {
        return $this->optionEnabled($this->currentOption);
    }

    /**
     * Otiene si una opción de crud está habilitada
     * @param string $option
     * @return boolean 
     */
    protected function optionEnabled($option)
    {
        return in_array($option, $this->enabledActions);
    }

    /**
     * Obtiene si la opción actual está activa
     */
    protected function checIfCurrentOptionEnabled()
    {
        $this->checkIfOptionEnabled($this->currentOption);
    }

    /**
     * Chequea si la operación está activa o lanza 404
     * @param string $option
     */
    protected function checkIfOptionEnabled($option)
    {
        if (!$this->enabledActions) {
            \App::abort(404);
        }
    }

    /**
     * Chequea si el usuario actual puede realizar una acción
     * @param string $action
     * @return boolean
     */
    public function can($action)
    {
        return $this->getUser()->can($this->constructOperationCompleteName($action));
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
            \App::abort(403);
        }
        return true;
    }

    public function canOrFailCurrentOption()
    {
        $this->canOrFail($this->currentOption);
    }

    /**
     * Hace todos los chequeos necesarios
     */
    public function checkAll()
    {
        $this->checIfCurrentOptionEnabled();
        $this->canOrFailCurrentOption();
    }

    // </editor-fold>
    // 
    // 
    // <editor-fold defaultstate="collapsed" desc="METODOS DEL CRUD">


    /**
     * @return GenericFilter
     */
    protected function getFilterInstance(Request $request)
    {
        return new GenericFilter();
    }



    protected function getPaginatorParam($name)
    {
        return request($this->paramNames[$name], $this->defaults[$name]);
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


    public function parseFilter(Request $request)
    {
        $filterStr = $request->get(config('filters.params.filter'));
        return $this->getFilterInstance($request)->fromBase64($filterStr);
    }

    /**
     * Listado de elementos
     * @param Request $request
     * @param type $config
     * @return \Illuminate\Pagination\Paginator
     */
    public function index(Request $request, $config = array())
    {
        return $this->paginatedIndex($request, $config);
    }

    protected function paginatedIndex(Request $request, $config = array())
    {
        $this->currentOption = 'list';
        $this->checkAll();
        $this->checIfCurrentOptionEnabled();

        $this->backUrl = $this->getRouteToAction($this->redirects['index'], array(), false);
        $this->setTitle($this->getPageTitle('index'));

        $filterStr = $this->getPaginatorParam('filter');
        $page = $this->getPaginatorParam('page');
        $size = $this->getPaginatorParam('page_size');

        $filter = $this->parseFilter($request);

        $with = isset($config['with']) ? $config['with'] : $this->defaultWith;
        $withCount = isset($config['withCount']) ? $config['withCount'] : $this->defaultWithCount;

        if (count($with)) {
            $filter->setWith($with);
        }

        $order = $this->getPaginatorParam('order');
        $filter->parseSortStr($order);

        $show = $this->getPaginatorParam('show');
        $filter->setShowFilter($show);
        $query = $this->getServiceInstance()->listObjectsByFilter($filter);
        if (count($withCount)) {
            $query->withCount($withCount);
        }

        $paginator = $query->paginate($size, ['*'], $this->paramNames['page'], $page);
        if ($paginator->currentPage() > 1 && $paginator->currentPage() > $paginator->lastPage()) {
            $currentPath = \Route::getFacadeRoot()->current()->getName();
            $request->merge([$this->paramNames['page'] => $paginator->lastPage()]);
            return redirect(route($currentPath, $request->all()));
        }

        //unset($filter['show']);


        //agrego los parámteros actuales al paginador
        $paginator = $paginator->appends([
            $this->paramNames['order'] => $order,
            $this->paramNames['page_size'] => $size,
            $this->paramNames['filter'] => $filterStr,
        ]);

        if ($show != 'active' && $this->showActiveFilter($request)) {
            $paginator = $paginator->appends([
                $this->paramNames['show'] => $show
            ]);
        }

        $data = $this->getDataView();

        $data['config'] = $config;
        $data['paginator'] = $paginator;
        $data['pageSizeOptions'] = $this->pageSizeOptions;
        $data['showActiveFilter'] = $this->showActiveFilter($request);
        $data['showParamNames'] = $this->showParamNames;
        $data['paramNames'] = $this->paramNames;
        $data['show'] = $show;
        $data['filter'] = $filter;
        $data = array_merge($data, $this->addDataToView($this->currentOption, $request, $data));
        return view($this->getViewName('index'), $data);
    }

    protected function unpaginatedIndex(Request $request, $config = array())
    {
        $this->currentOption = 'list';
        $this->checkAll();
        $this->checIfCurrentOptionEnabled();

        $this->backUrl = $this->getRouteToAction($this->redirects['index'], array(), false);
        $this->setTitle($this->getPageTitle('index'));
    }


    protected function showActiveFilter(Request $request)
    {
        return $this->useSoftDeletes && $this->canViewTrashed();
    }

    public function show(Request $request, $id, $config = array(), $data = array())
    {
        $this->currentOption = 'show';
        if (!$this->currentOptionEnabled()) {
            return redirect('404');
        }

        /*
        $this->backUrl = route($this->routeBaseName . 'index');
        echo $this->backUrl;
        //print_r($this->getServiceInstance()->findByPrimaryKey(1));*/
        return redirect($this->getRouteToAction('edit', [$this->getEditParamName() => $this->getIdFromRequest($request), 'back' => $this->backUrl]));
    }

    public function getEntityTitle($entity)
    {
        return $entity->name;
    }


    public function getIdFromRequest(Request $request)
    {
        $parmName = $this->getEditParamName();
        return $request->{$parmName};
    }

    /**
     * 
     * @param Request $request
     * @param type $id
     * @param type $config
     * @param type $data
     * @return type
     */
    public function edit(Request $request, $id, $config = array(), $data = array())
    {
        $this->currentOption = 'edit';
        $this->checkAll();





        //el usuario hizo click en cancelar
        if (request('cancel')) {
            $this->cancelCreation($request, $config, $data);
            return redirect($this->backUrl);
        }



        //obtengo el id
        $pk = $this->getIdFromRequest($request);

        $entity = $this->getServiceInstance()->findByPrimaryKey($pk, $this->canViewTrashed());
        if ($entity == null) {
            abort(404);
        }
        $this->setCurrentEntity($entity);

        $this->onAction($this->currentOption, $entity, $request);

        //seteo el breadcrumb
        $this->addBreadCrumb($this->getEntityTitle($entity), $this->getRouteToAction('edit', array($this->getEditParamName(), $pk)));
        $this->setTitle($this->getPageTitle('edit', ['name' => $this->getEntityTitle($entity)]));

        $viewData = $this->getDataView();
        //$viewData['title'] = $this->getEntityTitle($entity);
        $viewData['config'] = $config;
        $viewData['entity'] = $entity;
        $viewData = array_merge($viewData, $this->addDataToView($this->currentOption, $request, $viewData));



        $view = view($this->getViewName('edit'), $viewData);
        if (isset($data['errors'])) {
            $view->withErrors($data['errors']);
        }

        return $view;
    }

    public function update(Request $request, $id, $config = array(), $data = array())
    {
        $this->currentOption = 'edit';
        $this->checkAll();




        //el usuario hizo click en cancelar
        if (request('cancel')) {
            $this->cancelCreation($request, $config, $data);
            return redirect($this->backUrl);
        }


        //obtengo el id
        $pk = $this->getIdFromRequest($request);

        $entity = $this->getServiceInstance()->findByPrimaryKey($pk, $this->canViewTrashed());
        if ($entity == null) {
            abort(404);
        }
        $this->setCurrentEntity($entity);
        $this->onAction($this->currentOption, $entity, $request);
        try {

            $formData = $request->all();

            $formData[$this->getEditParamName()] = $pk;
            $formData = $this->parseForm($this->currentOption, $formData, $request, $config, $data);

            //llamo al servicio para guardar
            $entity = $this->getServiceInstance()->update($entity, $formData);
            //guardo la entidad en el array para pasarselo al método
            $data['entity'] = $entity;
            // redirect
            $this->setFlash('success', $this->getMessage('update_success'));

            return redirect($this->getRouteAfterAction('edit', $entity));
        } catch (\Illuminate\Validation\ValidationException $vex) {
            $this->setFlash('error', \Lang::get('The given data was invalid.'));
            $data['errors'] = $vex->validator;

            return redirect()->back()->withInput()->withErrors($vex->errors())->with('back', $this->backUrl);
        } catch (\Exception $ex) {
            $this->setFlash('error', $ex->getMessage());
            return redirect()->back()->withInput()->with('back', $this->backUrl);
        }
    }

    public function destroy(Request $request, $id, $config = array(), $data = array())
    {

        $this->currentOption = 'delete';
        $this->checkAll();
        $ret = array();
        $pk = $this->getIdFromRequest($request);
        try {
            $entity = $this->getServiceInstance()->findByPrimaryKey($pk, $this->canViewTrashed());
            if ($entity == null) {
                throw new \Exception($this->getMessage('element_not_found'));
            }
            $this->onAction($this->currentOption, $entity, $request);

            //si no estaba en la papelera lo elimino
            if ((!$this->useSoftDeletes) || (!$entity->trashed())) {
                $this->getServiceInstance()->delete($pk);
                if ($this->useSoftDeletes) {
                    //$this->setFlash('success', $this->getMessage('trash_success'));
                    if ((!$this->canViewTrashed()) || (!$this->useSoftDeletes)) {
                        $ret['go'] = request('back', $this->getRouteToAction('index'));
                    }
                } else {

                    //$this->setFlash('success', $this->getMessage('delete_success'));
                    $ret['go'] = request('back', $this->getRouteToAction('index'));
                }
            } else {
                $isRevert = $request->get('restore', false);

                if ($isRevert) {
                    if ($this->canOrFail('restore_trashed')) {
                        $this->getServiceInstance()->restore($pk);
                        //$this->setFlash('success', $this->getMessage('restore_success'));
                    }
                } else {
                    if ($this->canOrFail('delete_permanently')) {
                        $this->getServiceInstance()->delete($pk, true);
                        $ret['go'] = request('back', $this->getRouteToAction('index'));
                        //$this->setFlash('success', $this->getMessage('delete_success'));
                    }
                }
            }

            if (isset($ret['go'])) {
                $ret['go'] = url($ret['go']);
            }

            $ret['status'] = 'success';
            $ret['message'] = $this->getMessage('delete_success');
        } catch (\Exception $ex) {
            $ret['status'] = 'error';
            $ret['message'] = $ex->getMessage();
            return response(json_encode($ret), 500);
        }
        return json_encode($ret);
    }

    /**
     * 
     */
    public function cancelCreation(Request $request, $config = array(), $data = array())
    { }

    protected function getMessage($option, $data = array())
    {
        $message = '';
        switch ($option) {
            case 'creation_success':
            case 'delete_success':
            case 'update_success':
            case 'element_not_found':
                if (\Lang::has('generics.' . $this->moduleName . '.' . $option)) {
                    $message = \Lang::get('generics.' . $this->moduleName . '.' . $option);
                } else {
                    $message = \Lang::get('generics.' . $option);
                }
                break;
        }
        return $message;
    }

    /**
     * Método para mostrar el formulario de creación
     * @param Request $request
     * @param type $config
     */
    public function create(Request $request, $config = array(), $data = array())
    {
        $this->currentOption = 'create';
        $this->checkAll();


        //el usuario hizo click en cancelar
        if (request('cancel')) {
            $this->cancelCreation($request, $config, $data);
            return redirect(url($this->backUrl));
        }


        $this->addBreadCrumb($this->getPageTitle('create'), $this->getRouteToAction('create'));
        $this->setTitle($this->getPageTitle('create'));
        $method = $request->method();
        if (!isset($data['entity']) || $data['entity'] == null) {
            $entity = $this->getNewModelInstance();
        } else {
            $entity = $data['entity'];
        }
        $this->setCurrentEntity($entity);
        $this->onAction($this->currentOption, $entity, $request);
        $viewData = $this->getDataView();
        $viewData['config'] = $config;
        $viewData['entity'] = $entity;
        $viewData = array_merge($viewData, $this->addDataToView($this->currentOption, $request, $viewData));
        $view = view($this->getViewName('create'), $viewData);
        if (isset($data['errors'])) {
            $view->withErrors($data['errors']);
        }

        return $view;
    }

    public function store(Request $request, $config = array(), $data = array())
    {
        $this->currentOption = 'create';
        $this->checkAll();


        //el usuario hizo click en cancelar
        if (request('cancel')) {
            $this->cancelCreation($request, $config, $data);
            return redirect(url($this->backUrl));
        }

        if (!isset($data['entity']) || $data['entity'] == null) {
            $entity = $this->getNewModelInstance();
        } else {
            $entity = $data['entity'];
        }
        $this->onAction($this->currentOption, $entity, $request);
        try {
            //parseo la entidad
            $formData = \Illuminate\Support\Facades\Input::all();

            $entity = $this->parseForm($this->currentOption, $formData, $request, $config, $data);
            //guardo la entidad en el array para pasarselo al método
            $data['entity'] = $entity;

            //llamo al servicio para guardar
            $entity = $this->getServiceInstance()->create($formData);
            $this->setCurrentEntity($entity);
            //guardo la entidad en el array para pasarselo al método
            $data['entity'] = $entity;
            // redirect
            $this->setFlash('success', $this->getMessage('creation_success'));
            return redirect($this->getRouteAfterAction('create', $entity));
        } catch (\Illuminate\Validation\ValidationException $vex) {
            $this->setFlash('error', \Lang::get('The given data was invalid.'));
            $data['errors'] = $vex->validator;

            return redirect()->back()->withInput()->withErrors($vex->errors())->with('back', $this->backUrl);
        } catch (\Exception $ex) {
            $this->setFlash('error', $ex->getMessage());
            return redirect()->back()->withInput()->with('back', $this->backUrl);
        }
    }

    public function onAction($option, $entity, Request $request)
    { }

    public function createValidator($data = array())
    {
        return Validator::make($data, []);
    }

    public function createdSuccessFully(Request $request, $object)
    {
        return redirect($this->routeBaseName . $this->redirects['create']);
    }

    // </editor-fold>

    /**
     * Obtiene los datos básicos para la vista
     * @return type
     */
    public function getDataView()
    {
        $ret = parent::getDataView();
        $ret['modelName'] = $this->modelName;
        $ret['moduleName'] = $this->moduleName;
        $ret['routeBaseName'] = $this->routeBaseName;
        $ret['viewBaseName'] = $this->viewBaseName;
        $ret['crudConfigKey'] = $this->crudConfigKey;
        $ret['editParamName'] = $this->getEditParamName();
        $ret['appId'] = $this->appId;
        $ret['currentOption'] = $this->currentOption;
        $ret['enabledActions'] = $this->enabledActions;
        $ret['headerButtons'] = array(
            'create' => $this->can('create'),
        );
        $ret['actions'] = array(
            'base' => url(\Route::current()->uri()),
            'back' => $this->backUrl,
            'cancel' => '?cancel=1&back=' . urlencode($this->backUrl),
            'create' => $this->getRouteToAction('create'),
        );
        return $ret;
    }
}
