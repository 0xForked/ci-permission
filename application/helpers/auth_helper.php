<?php

if(! function_exists("isActive")) {
    function isActive($status)
    {
        return (int)$status === 1 ? true : false;
    }
}

if(! function_exists("check")) {
    function check()
    {
        $auth = new Auth();
        return $auth->loginStatus();
    }
}

//if( can('edit-posts') ) {}
//if( can(['edit-posts', 'publish-posts']) ) {}
if(! function_exists("can")) {
    function can($permissions)
    {
        $auth = new Auth();
        return $auth->can($permissions);
    }
}

//if( hasRoles(['admin', 'editor']) ) {}
//if( hasRoles('subscriber') ) {}   
if(! function_exists("hasRole")) {
    function hasRole($roles)
    {
        $auth = new Auth();
        return $auth->hasRole($roles);
    }
}