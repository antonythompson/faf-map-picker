<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class ResourceController extends AppController
{
    protected $id;
    protected $translatedMessages;
    protected $authenticated;
    protected $resourceModel;

    public function __construct(Request $request)
    {
        $this->authenticated = $request->user();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->resourceModel::query();
        # load relationships

        $relationships = $this->getRelationshipWith($request);
        if ($relationships) {
            $query = $query->with($relationships);
        }

        # load sorting
        if (method_exists($this->resourceModel, 'sort')) {
            $sortField = $request->get('sort');
            $sortType = $request->get('sortType');
            $query = $this->resourceModel::sort($query, $sortField, $sortType);
        }


        # load filtering
        if (method_exists($this->resourceModel, 'filter')) {
            $filters = [];
            if ($request->has('filters')) {
                $filters = $this->convertParamIfJSON($request->input('filters'));
            }
            $query = $this->resourceModel::filter($query, $filters);
        }

        # Search
        $searchText = $request->get('search');
        if ($searchText) {
            $search_ids = $this->resourceModel::search($searchText)->keys()->toArray();
            $query = $query->whereIn('id', $search_ids);
        }


        # load extra logic from child class
        if (method_exists($this, 'afterIndex')) {
            $query = $this->afterIndex($query);
        }



        # Paginate and response
        if ((isset($this->paginate) && $this->paginate) || $request->perPage != 'false') {
            $result = $query->paginate(intval($request->perPage));
        } else {
            $result = [ 'data' => $query->get() ];
        }

        return response()->json($result, 200);
    }

    public function destroy(Request $request, $id)
    {
        $this->id = (int) $id;

        if (method_exists($this, 'beforeDestroy') && !$this->beforeDestroy($request, $id)) {
            return response()->json(['error' => 'Unable to delete'], 500);
        }

        $resource = $this->resourceModel::destroy($id);
        if ($resource) {
            if (isset($this->afterDestroy) && is_array($this->afterDestroy)) {
                foreach ($this->afterDestroy as $method) {
                    $this->$method($id);
                }
            }
            return response()->json(null, 204);
        } else {
            return response()->json('Unable to delete', 400);
        }
    }

    public function show(Request $request, $id)
    {
        $this->id = (int) $id;
        $query = $this->resourceModel::query();
        $relationships = $this->getRelationshipWith($request);
        if ($relationships) {
            $query = $query->with($relationships);
        }
        $resource = $query->find($id);
        if ($resource) {
            return response()->json(['data' => $resource], 200);
        } else {
            return response()->json('Unable to find', 400);
        }
    }

    public function store(Request $request)
    {
        $data = $this->resourceModel::validateStore($request);
        $resource = $this->resourceModel::create($data);
        if ($resource) {
            return response()->json(['data' => $resource], 200);
        } else {
            return response()->json('Unable to create', 400);
        }
    }

    public function update(Request $request, $id)
    {
        $this->id = (int) $id;
        $data = $this->resourceModel::validateUpdate($request);
        $resource = $this->resourceModel::find($id);
        if ($resource) {
            if ($this->save($resource, $data)){
                return response()->json(['data' => $resource], 200);
            }
            return response()->json('Could not save', 400);
        } else {
            return response()->json('Unable to find', 400);
        }
    }

    public function save($resource, $data)
    {
        $resource->fill($data);
        if (!$resource->save()){
            return false;
        }
        if ($this->resourceModel::$loadable) {
            foreach ($this->resourceModel::$loadable as $field) {
                if (isset($data[$field])) {
                    $model = new $this->resourceModel;
                    $relationship = $model->$field();
                    if ($relationship instanceof BelongsToMany && is_array($data[$field])) {
                        $resource->$field()->sync($data[$field]);
                    }
                    //TODO add more relationship types....
                }
            }
        }
        return true;
    }

    protected function getRelationshipWith($request)
    {
        if ($request->has('load') || $this->resourceModel::$defaultLoaded) {
            $relationships = $this->resourceModel::$loadable;
            if ($request->has('load')) {
                $loadRelationship = explode(',', $request->load);
            } else {
                $loadRelationship = $this->resourceModel::$defaultLoaded;
            }
            if ($loadRelationship[0] != 'all') {
                foreach ($relationships as $index => $relationship) {
                    if (!in_array($relationship, $loadRelationship)) {
                        unset($relationships[$index]);
                    }
                }
            }
            return $relationships;
        }
        return false;
    }

    protected function filteredLoad($load, $userId)
    {
        $result = [];
        if (isset($this->resourceModel::$filteredLoadable)) {
            if (is_array($load)) {
                foreach ($load as $loadValue) {
                    if (isset($this->resourceModel::$filteredLoadable[$loadValue])) {
                        $key = $this->resourceModel::$filteredLoadable[$loadValue];
                        $result[$loadValue] = function ($query) use ($userId, $key) {
                            $query->where($key, '=', $userId);
                        };
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param mixed $param
     *
     * @return array
     */
    protected function convertParamIfJSON($param)
    {
        $result = [];
        if (is_array($param)) {
            $result = $param;
        } else {
            $param = json_decode($param, 1);
            if (!empty($param)) {
                $result = $param;
            }
        }
        return $result;
    }
}