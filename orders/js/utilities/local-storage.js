class localStorage{
        
    //Save an object into local storage
    SetLocalStorage(id, value){
        window.localStorage.setItem(id, JSON.stringify(value));
    }
    
    //Delete an object from local storage
    RemoveLocalStorage(id){
        window.localStorage.removeItem(id);
    }
    
    //Retrieve an object from local storage
    GetLocalStorage(id){
        var sort_options = JSON.parse(window.localStorage.getItem(id));
        return sort_options;
    }    
}

const local_storage = new localStorage();