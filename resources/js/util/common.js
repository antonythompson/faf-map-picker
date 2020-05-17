import LaravelRouter from "../thirdparty/Ziggy/route";

export {
    splitObjKey,
    objLookup,
    handleHttpError,
    getExceptionErrorMessage,
    getHttpValidationErrors,
    numberWithCommas,
    submitHiddenForm,
    objToUrl,
    laravelRoute,
    arrayRemove,
};

function splitObjKey(key) {
    let type = typeof key;
    if (type == "string" || type == "number") {
        key = ("" + key).replace(/\[(.*?)\]/g, function (m, key) { //handle case where [1] or ['xa'] may occur
            return "." + key.replace(/^["']|["']$/g, ""); //strip quotes at the start or end of the key
        }).split(".");
    }
    return key;
}

function objLookup(obj, key) {
    key = splitObjKey(key);
    let i = 0, length;
    for (length = key.length; i < length; i++) {
        if (_.has(obj, key[i])) obj = obj[key[i]];
        else return void 0;
    }
    return obj;
}

function toCssClass(input) {
    if (!Array.isArray(input)) {
        input = input.split(',')
    }
    input.forEach(item => {

    });
}

function getExceptionErrorMessage(e) {
    if (typeof e === 'string') {
        return e;
    }
    let message = objLookup(e, 'response.data.message');
    if (!message) {
        message = objLookup(e, 'data.message');
    }
    if (!message) {
        message = objLookup(e, 'message');
    }
    if (!message) {
        message = 'There was a problem with your request.';
    }
    return message;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function getHttpValidationErrors(e) {
    if (typeof e === 'string') {
        return {general: [e]};
    }
    let errors = objLookup(e, 'errors');
    if (errors) {
        return errors;
    }
    return false;
}

function handleHttpError(e) {
    let message = getExceptionErrorMessage(e);
    if (message) {
        alert(message);
    }
}

/**
 * @param url action of the form
 * @param fields object {field_name => 'value'}
 * @param method HTTP method
 */
function submitHiddenForm(url, fields = null, method = 'POST') {
    let form = document.createElement('form');
    form.action = url;
    form.method = method;
    form.style = 'display: none;';
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        if (!fields) {
            fields = {'_token': token.content}
        } else {
            fields._token = token.content;
        }
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    if (fields) {
        for (let field in fields) {
            let element = document.createElement('input');
            element.type = 'hidden';
            element.name = field;
            element.value = fields[field];
            form.appendChild(element);
        }
    }
    document.body.appendChild(form);
    form.submit();
}

/**
 * This will convert an object into a url recursively
 * eg: {filter:{dir:184},b:'a'} into "filter[dir]=188&b=a"
 * @param o
 * @returns {string}
 */
function objToUrl(o) {

    let iter = function (o, path) {
        if (Array.isArray(o)) {
            o.forEach(function (a) {
                iter(a, path + '[]');
            });
            return;
        }
        if (o !== null && typeof o === 'object') {
            Object.keys(o).forEach(function (k) {
                iter(o[k], path + '[' + k + ']');
            });
            return;
        }
        data.push(path + '=' + o);
    }

    let data = [];
    Object.keys(o).forEach(function (k) {
        iter(o[k], k);
    });
    return data.join('&');
}

function laravelRoute (name, args = [], abs = true) {
    let url = null;
    try{
        url =  LaravelRouter(name, args, abs).url();
    } catch(e) {
        console.warn(e);
    }
    return url;
};

/**
 * Remove a value from an array.
 *
 * @param array
 * @param value
 * @param new_array set to true if you don't want to affect the original array
 * @returns []
 */
function arrayRemove(array, value, new_array = false){
    let index = array.indexOf(value);
    if (index !== -1){
        if (new_array){
            let new_arr = JSON.parse(JSON.stringify(array));
            new_arr.splice(index, 1);
            return new_arr;
        } else {
            array.splice(index, 1);
            return array;
        }
    }
    return array;
}