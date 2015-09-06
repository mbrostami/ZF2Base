<?php 
namespace ZF2Base\Adapters;

use Zend\ServiceManager\ServiceManager;
use ZF2Base\Abstracts\AbstractResourceDbAdapter;

/** 
 * Resource db adapter is for getting resources from database
 * 
 * @author MB Rostami <mb.rostami.h@gmail.com>
 */
class DefaultResourceDbAdapter extends AbstractResourceDbAdapter
{ 
    /**
     * {@inheritDoc}
     */
    public function checkResource($resource)
    {
        $this->getAllowedResources(); 
        if ($this->allowedResources) {
            if (array_key_exists($resource, $this->allowedResources)) {
                $this->lastMatchedResource = $resource;
                return true;
            } else { 
                foreach ($this->allowedResources as $allowedResource => $resourceValue) {
                    // If $allowedResource is not a valid pattern we'll get a warning
                    // For ignoring this warning we should use set_error_handler
                    set_error_handler(function ($errorNo, $errorString) {
                        if ($errorNo == 2) {
                            return ;
                        }
                    }, E_WARNING);
                    if (preg_match("/$allowedResource/i", $resource)) {
                        $this->lastMatchedResource = $allowedResource;
                        return true;
                    }
                    restore_error_handler();
                } 
            }
        }
        return false;
    } 
    
    /**
     * {@inheritDoc}
     */
    public function getAllResources()
    { 
        if ($this->allResources) {
            return $this->allResources;
        }
        $this->getResources();
        $this->getSubResources(); 
        if ($this->resources) {
            foreach ($this->resources as $resourceName => $resource) { 
                $this->allResources[$resourceName] = $resource;
                if ($this->subResources && array_key_exists($resourceName, $this->subResources)) {
                    foreach ($this->subResources as $subResource) {
                        $this->allResources[$subResource['sub_resource']] = $subResource;
                    }
                }
            }
        } 
        return $this->allResources;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAllowedResources()
    {  
        if ($this->allowedResources) {
            return $this->allowedResources;
        }
        // Get default resources
        $this->getResources();
        $this->getSubResources(); 
        if ($this->resources) {
            foreach ($this->resources as $resourceName => $resource) {
                if ($resource['type'] !== 'default') {
                    continue;
                }
                $this->allowedResources[$resourceName] = true;
                if ($this->subResources && array_key_exists($resourceName, $this->subResources)) {
                    foreach ($this->subResources[$resourceName] as $subResource) {
                        $this->allowedResources[$subResource['sub_resource']] = true;
                    }
                }
            }
        } 
        

        if ($this->userData) {
            // Get user and users groups resources and merge these two together
            $this->getGroupResources($this->userData->id); 
            $this->getUserResources($this->userData->id); 
            $allResources = array_merge($this->groupResources, $this->userResources);
            if ($allResources) {
                foreach ($allResources as $resourceName => $resourceValue) {
                    // Deny access for resources which has deny value
                    if ($resourceValue === 'deny') {
                        $this->deniedResources[$resourceName] = $resourceValue;
                        if ($this->subResources && array_key_exists($resourceName, $this->subResources)) {
                            foreach ($this->subResources[$resourceName] as $subResource) {
                                $this->deniedResources[$subResource['sub_resource']] = 'deny';
                            }
                        }
                        continue;
                    }
                    $this->allowedResources[$resourceName] = $resourceValue;
                }
            }   
        }
        return $this->allowedResources;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDeniedResources()
    { 
        if ($this->deniedResources) {
            return $this->deniedResources;
        }
        if ($this->userData) {
            // Get user resources for cheking deny values. 
            // NOTE: group resources don't have deny values | logical
            $this->getUserResources($this->userData->id); 
            if ($this->userResources) {
                foreach ($this->userResources as $resourceName => $resourceValue) {
                    // Deny access for resources which has deny value
                    if ($resourceValue === 'deny') {
                        $this->deniedResources[$resourceName] = $resourceValue; 
                        if ($this->subResources && array_key_exists($resourceName, $this->subResources)) {
                            foreach ($this->subResources[$resourceName] as $subResource) {
                                $this->deniedResources[$subResource['sub_resource']] = 'deny';
                            }
                        }
                    } 
                }
            }  
        }
        return $this->deniedResources;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getGroupResources($userId)
    {
        if ($this->groupResources) {
            return $this->groupResources;
        }
        $this->groupResources = array();
        $this->getSubResources();
        $groupPermissionsTable = $this->modelFactory->getModel('ZF2Base\Models\Table\GroupPermissionsTable');
        $resources = $groupPermissionsTable->getGroupsResources($userId);
        if ($resources) {
            foreach ($resources as $resource) {
                $this->groupResources[$resource['resource']] = $resource['value'];
                if ($this->subResources && array_key_exists($resource['resource'], $this->subResources)) {
                    foreach ($this->subResources[$resource['resource']] as $subResource) {
                        $this->groupResources[$subResource['sub_resource']] = true;
                    }
                }
            }
        }
        return $this->groupResources;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getUserResources($userId)
    { 
        if ($this->userResources) {
            return $this->userResources;
        }
        $this->userResources = array();
        $userPermissionsTable = $this->modelFactory->getModel('ZF2Base\Models\Table\UserPermissionsTable');
        $resources = $userPermissionsTable->getUsersResources($userId); 
        if ($resources) {
            foreach ($resources as $resource) {
                $this->userResources[$resource['resource']] = $resource['value'];
                if ($this->subResources && array_key_exists($resource['resource'], $this->subResources)) {
                    foreach ($this->subResources[$resource['resource']] as $subResource) {
                        $this->userResources[$subResource['sub_resource']] = true;
                    }
                }
            }
        }
        return $this->userResources;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getResources()
    {
        if ($this->resources) {
            return  $this->resources;
        }
        $resourcesTable = $this->modelFactory->getModel('ZF2Base\Models\Table\ResourcesTable');
        $resources = $resourcesTable->getResources(); 
        if ($resources) {
            foreach ($resources as $resource) {
                $this->resources[$resource['resource']] = $resource;
            }
        }
        return  $this->resources;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getSubResources()
    {
        if ($this->subResources) {
            return  $this->subResources;
        }
        $subResourcesTable = $this->modelFactory->getModel('ZF2Base\Models\Table\SubResourcesTable');
        $resources = $subResourcesTable->getSubResources(); 
        if ($resources) {
            foreach ($resources as $resource) { 
                $this->subResources[$resource['resource']][] = $resource;
            }
        }
        return  $this->subResources;
    } 
}