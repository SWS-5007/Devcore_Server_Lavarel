<?php


namespace App\Lib\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Lib\Models\Resources\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ResourcesController extends Controller
{


    public function getConfigValue($section, $key)
    {
        return config('resources.' . $section . '.' . $key, config('resources.default.' . $key));
    }


    public function store(Request $request, Response $response)
    {
        $rawParams = $request->parameters;
        $params = explode("/", $rawParams);
        if (count($params) < 2) {
            abort(404);
        }

        //get the section
        $section =  array_shift($params);
        //get the owner id
        $ownerId = array_pop($params);

        $class = $this->getConfigValue($section, 'type');
        $resource = new $class();
        $resource->owner_id = $ownerId;
        $resource->owner_type = $this->getConfigValue($section, 'owner-type');
        $resource->display_type = $this->getConfigValue($section, 'display-type');
        $resource->visibility = $this->getConfigValue($section, 'visibility');
        $resource->type = $class;
        $resource->saveFile($request->file('file'));
        $resource->save();
        return [
            'id' => $resource->id,
            'uri' => $resource->uri,
            'url' => $resource->url,
            'thumbUrl' => $resource->thumbUrl,
            'mime_type' => $resource->mime_type,
            'size' => $resource->size
        ];
    }
    public function update(Request $request, Response $response)
    { }

    public function show(Request $request, Response $response)
    {
        $resource = Resource::where('uuid', $request->id)->firstOrFail();
        return $resource->getFile()->downloadFile();
    }

    public function showPrivate(Request $request, Response $response)
    {
        $params = Crypt::decrypt($request->parameters);
        if ($this->getUser()->id != $params['user_id']) {
            abort(403, __('You are not allowed to see this content'));
        }

        $resource = Resource::findOrFail($params['id']);
        @ob_end_clean();
        return $resource->getFile()->downloadFile();
    }
    public function destroy(Request $request, Response $response)
    {
        //we need to use get and then delete!
        $resource=Resource::where('uuid', $request->id)->firstOrFail();
        $resource->delete();
        return [
            'success' => true,
        ];
    }
}
