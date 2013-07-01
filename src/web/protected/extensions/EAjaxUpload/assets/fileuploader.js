/**
 * http://github.com/valums/file-uploader
 *
 * Multiple file upload component with progress-bar, drag-and-drop.
 * © 2010 Andrew Valums ( andrew(at)valums.com )
 *
 * Licensed under GNU GPL 2 or later, see license.txt.
 */

/**
* funciones auxiliares
*/

var qq = qq || {};

/**
 * Añade todas la propiedas perdidas del segundo objeto al primer objeto
 */
qq.extend = function(first, second)
{
    for (var prop in second)
    {
        first[prop] = second[prop];
    }
};

/**
 * Busca un elemento dado de la matriz, devuelve -1 si no está presente.
 * @param {Number} [from] El índice en el que comenzará la búsqueda
 */
qq.indexOf = function(arr, elt, from)
{
    if (arr.indexOf) return arr.indexOf(elt, from);

    from = from || 0;
    var len = arr.length;

    if (from < 0) from += len;

    for (; from < len; from++)
    {
        if(from in arr && arr[from] === elt)
        {
            return from;
        }
    }
    return -1;
};

qq.getUniqueId = (function()
{
    var id = 0;
    return function()
    {
        return id++;
    };
})();

/**
* Eventos
*/
qq.attach = function(element, type, fn)
{
    if (element.addEventListener)
    {
        element.addEventListener(type, fn, false);
    }
    else if(element.attachEvent)
    {
        element.attachEvent('on' + type, fn);
    }
};
qq.detach = function(element, type, fn)
{
    if (element.removeEventListener)
    {
        element.removeEventListener(type, fn, false);
    }
    else if(element.attachEvent)
    {
        element.detachEvent('on' + type, fn);
    }
};

qq.preventDefault = function(e)
{
    if (e.preventDefault)
    {
        e.preventDefault();
    }
    else
    {
        e.returnValue = false;
    }
};

/**
* Node manipulations
*/

/**
 * Inserta un nodo antes del nodo b.
 */
qq.insertBefore = function(a, b)
{
    b.parentNode.insertBefore(a, b);
};
qq.remove = function(element)
{
    element.parentNode.removeChild(element);
};

qq.contains = function(parent, descendant)
{
    // ComparePosition devuelve false en este caso
    if (parent == descendant) return true;

    if (parent.contains)
    {
        return parent.contains(descendant);
    }
    else
    {
        return !!(descendant.compareDocumentPosition(parent) & 8);
    }
};

/**
 * Crea y devuelve elemento de cadena HTML
 * Utiliza innerHTML para crear un elemento
 */
qq.toElement = (function()
{
    var div = document.createElement('div');
    return function(html)
    {
        div.innerHTML = html;
        var element = div.firstChild;
        div.removeChild(element);
        return element;
    };
})();

/**
* Propiedades de nodo y atributos
*/

/**
 * Establece estilos para un elemento.
 * Corrige la opacidad en IE6-8.
 */
qq.css = function(element, styles)
{
    if (styles.opacity != null)
    {
        if (typeof element.style.opacity != 'string' && typeof(element.filters) != 'undefined')
        {
            styles.filter = 'alpha(opacity=' + Math.round(100 * styles.opacity) + ')';
        }
    }
    qq.extend(element.style, styles);
};
qq.hasClass = function(element, name)
{
    var re = new RegExp('(^| )' + name + '( |$)');
    return re.test(element.className);
};
qq.addClass = function(element, name)
{
    if (!qq.hasClass(element, name))
    {
        element.className += ' ' + name;
    }
};
qq.removeClass = function(element, name)
{
    var re = new RegExp('(^| )' + name + '( |$)');
    element.className = element.className.replace(re, ' ').replace(/^\s+|\s+$/g, "");
};
qq.setText = function(element, text)
{
    element.innerText = text;
    element.textContent = text;
};

/**
* Selección de los elementos
*/

qq.children = function(element)
{
    var children = [],
    child = element.firstChild;

    while (child)
    {
        if (child.nodeType == 1)
        {
            children.push(child);
        }
        child = child.nextSibling;
    }

    return children;
};

qq.getByClass = function(element, className)
{
    if (element.querySelectorAll)
    {
        return element.querySelectorAll('.' + className);
    }

    var result = [];
    var candidates = element.getElementsByTagName("*");
    var len = candidates.length;

    for (var i = 0; i < len; i++)
    {
        if (qq.hasClass(candidates[i], className))
        {
            result.push(candidates[i]);
        }
    }
    return result;
};

/**
 * obj2url() takes a json-object as argument and generates
 * a querystring. pretty much like jQuery.param()
 *
 * how to use:
 *
 *    `qq.obj2url({a:'b',c:'d'},'http://any.url/upload?otherParam=value');`
 *
 * will result in:
 *
 *    `http://any.url/upload?otherParam=value&a=b&c=d`
 *
 * @param  Object JSON-Object
 * @param  String current querystring-part
 * @return String encoded querystring
 */
qq.obj2url = function(obj, temp, prefixDone)
{
    var uristrings = [],
        prefix = '&',
        add = function(nextObj, i)
        {
            var nextTemp = temp
                ? (/\[\]$/.test(temp)) // prevent double-encoding
                   ? temp
                   : temp+'['+i+']'
                : i;
            if ((nextTemp != 'undefined') && (i != 'undefined'))
            {
                uristrings.push(
                    (typeof nextObj === 'object')
                        ? qq.obj2url(nextObj, nextTemp, true)
                        : (Object.prototype.toString.call(nextObj) === '[object Function]')
                            ? encodeURIComponent(nextTemp) + '=' + encodeURIComponent(nextObj())
                            : encodeURIComponent(nextTemp) + '=' + encodeURIComponent(nextObj)
                );
            }
        };

    if (!prefixDone && temp)
    {
      prefix = (/\?/.test(temp)) ? (/\?$/.test(temp)) ? '' : '&' : '?';
      uristrings.push(temp);
      uristrings.push(qq.obj2url(obj));
    }
    else if((Object.prototype.toString.call(obj) === '[object Array]') && (typeof obj != 'undefined'))
    {
        // no utilizaremos un for-in-loop en una matriz (performance)
        for (var i = 0, len = obj.length; i < len; ++i)
        {
            add(obj[i], i);
        }
    }
    else if((typeof obj != 'undefined') && (obj !== null) && (typeof obj === "object"))
    {
        // para cualquier otra cosa, pero un escalar, que usaremos para-in-loop
        for (var i in obj)
        {
            add(obj[i], i);
        }
    }
    else
    {
        uristrings.push(encodeURIComponent(temp) + '=' + encodeURIComponent(obj));
    }

    return uristrings.join(prefix)
                     .replace(/^&/, '')
                     .replace(/%20/g, '+');
};

/**
* Clases Uploader
*/
var qq = qq || {};

/**
 * Crea botón de subida, valida archivo subidos, pero no crea lista de archivos o dd.
 */
qq.FileUploaderBasic = function(o)
{
    this._options = {
        // establecido en true para ver la respuesta del servidor
        debug: false,
        action: '/server/upload',
        params: {},
        button: null,
        multiple: true,
        maxConnections: 3,
        // validacion
        allowedExtensions: [],
        sizeLimit: 0,
        minSizeLimit: 0,
        // eventos
        // retorna falso para cancelar envio
        onSubmit: function(id, fileName){},
        onProgress: function(id, fileName, loaded, total){},
        onComplete: function(id, fileName, responseJSON){},
        onCancel: function(id, fileName){},
        // mensajes
        messages: {
            typeError: "{file} has invalid extension. Only {extensions} are allowed.",
            sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
            minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
            emptyError: "{file} is empty, please select files again without it.",
            onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
        },
        showMessage: function(message)
        {
            alert(message);
        }
    };
    qq.extend(this._options, o);

    // numero de archivos que seran subidos
    this._filesInProgress = 0;
    this._handler = this._createUploadHandler();

    if (this._options.button)
    {
        this._button = this._createUploadButton(this._options.button);
    }

    this._preventLeaveInProgress();
};

qq.FileUploaderBasic.prototype = {
    setParams: function(params)
    {
        this._options.params = params;
    },
    getInProgress: function()
    {
        return this._filesInProgress;
    },
    _createUploadButton: function(element)
    {
        var self = this;

        return new qq.UploadButton({
            element: element,
            multiple: this._options.multiple && qq.UploadHandlerXhr.isSupported(),
            onChange: function(input)
            {
                self._onInputChange(input);
            }
        });
    },
    _createUploadHandler: function()
    {
        var self = this,
            handlerClass;

        if(qq.UploadHandlerXhr.isSupported())
        {
            handlerClass = 'UploadHandlerXhr';
        }
        else
        {
            handlerClass = 'UploadHandlerForm';
        }

        var handler = new qq[handlerClass]({
            debug: this._options.debug,
            action: this._options.action,
            maxConnections: this._options.maxConnections,
            onProgress: function(id, fileName, loaded, total)
            {
                self._onProgress(id, fileName, loaded, total);
                self._options.onProgress(id, fileName, loaded, total);
            },
            onComplete: function(id, fileName, result)
            {
                self._onComplete(id, fileName, result);
                self._options.onComplete(id, fileName, result);
            },
            onCancel: function(id, fileName)
            {
                self._onCancel(id, fileName);
                self._options.onCancel(id, fileName);
            }
        });

        return handler;
    },
    _preventLeaveInProgress: function()
    {
        var self = this;

        qq.attach(window, 'beforeunload', function(e)
        {
            if(!self._filesInProgress)
            {
                return;
            }

            var e = e || window.event;
            // for ie, ff
            e.returnValue = self._options.messages.onLeave;
            // for webkit
            return self._options.messages.onLeave;
        });
    },
    _onSubmit: function(id, fileName)
    {
        this._filesInProgress++;
    },
    _onProgress: function(id, fileName, loaded, total)
    {
    },
    _onComplete: function(id, fileName, result)
    {
        this._filesInProgress--;
        if (result.error)
        {
            this._options.showMessage(result.error);
        }
    },
    _onCancel: function(id, fileName)
    {
        this._filesInProgress--;
    },
    _onInputChange: function(input)
    {
        if (this._handler instanceof qq.UploadHandlerXhr)
        {
            this._uploadFileList(input.files);
        }
        else
        {
            if(this._validateFile(input))
            {
                this._uploadFile(input);
            }
        }
        this._button.reset();
    },
    _uploadFileList: function(files)
    {
        for (var i=0; i<files.length; i++)
        {
            if( !this._validateFile(files[i]))
            {
                return;
            }
        }

        for (var i=0; i<files.length; i++)
        {
            this._uploadFile(files[i]);
        }
    },
    _uploadFile: function(fileContainer)
    {
        var id = this._handler.add(fileContainer);
        var fileName = this._handler.getName(id);

        if(this._options.onSubmit(id, fileName) !== false)
        {
            this._onSubmit(id, fileName);
            this._handler.upload(id, this._options.params);
        }
    },
    _validateFile: function(file)
    {
        var name, size;

        if (file.value)
        {
            // se trata de un archivo de entrada
            // obtener el valor de entrada y quite ruta para normalizar
            name = file.value.replace(/.*(\/|\\)/, "");
        }
        else
        {
            // fijar propiedades que faltan en Safari
            name = file.fileName != null ? file.fileName : file.name;
            size = file.fileSize != null ? file.fileSize : file.size;
        }

        if(! this._isAllowedExtension(name))
        {
            this._error('typeError', name);
            return false;
        }
        else if(size === 0)
        {
            this._error('emptyError', name);
            return false;
        }
        else if(size && this._options.sizeLimit && size > this._options.sizeLimit)
        {
            this._error('sizeError', name);
            return false;
        }
        else if(size && size < this._options.minSizeLimit)
        {
            this._error('minSizeError', name);
            return false;
        }

        return true;
    },
    _error: function(code, fileName)
    {
        var message = this._options.messages[code];
        function r(name, replacement)
        {
            message = message.replace(name, replacement);
        }

        r('{file}', this._formatFileName(fileName));
        r('{extensions}', this._options.allowedExtensions.join(', '));
        r('{sizeLimit}', this._formatSize(this._options.sizeLimit));
        r('{minSizeLimit}', this._formatSize(this._options.minSizeLimit));

        this._options.showMessage(message);
    },
    _formatFileName: function(name)
    {
        if(name.length > 33)
        {
            name = name.slice(0, 19) + '...' + name.slice(-13);
        }
        return name;
    },
    _isAllowedExtension: function(fileName)
    {
        var ext = (-1 !== fileName.indexOf('.')) ? fileName.replace(/.*[.]/, '').toLowerCase() : '';
        var allowed = this._options.allowedExtensions;

        if(!allowed.length)
        {
            return true;
        }

        for(var i=0; i<allowed.length; i++)
        {
            if(allowed[i].toLowerCase() == ext)
            {
                return true;
            }
        }

        return false;
    },
    _formatSize: function(bytes)
    {
        var i = -1;
        do
        {
            bytes = bytes / 1024;
            i++;
        }
        while(bytes > 99);

        return Math.max(bytes, 0.1).toFixed(1) + ['kB', 'MB', 'GB', 'TB', 'PB', 'EB'][i];
    }
};


/**
 * Clase que crea widget de carga con un simple arrastrar y soltar y la lista de archivos
 * @inherits qq.FileUploaderBasic
 */
qq.FileUploader = function(o)
{
    // llame al constructor padre
    qq.FileUploaderBasic.apply(this, arguments);

    // opciones adicionales
    qq.extend(this._options, {
        element: null,
        // si se establece, se utilizará en lugar de q-upload-lista en la plantilla
        listElement: null,

        template: '<div class="qq-uploader">' +
                '<div class="qq-upload-drop-area"><span>Soltar archivos para cargar</span></div>' +
                '<div class="qq-upload-button">Cargar archivos</div>' +
                '<ul class="qq-upload-list"></ul>' +
             '</div>',

        // plantilla para un elemento en la lista de archivos
        fileTemplate: '<li>' +
                '<span class="qq-upload-file"></span>' +
                '<span class="qq-upload-spinner"></span>' +
                '<span class="qq-upload-size"></span>' +
                '<a class="qq-upload-cancel" href="#">Cancel</a>' +
                '<span class="qq-upload-failed-text">Fallo</span>' +
            '</li>',

        classes: {
            // utilizado para obtener los elementos de las plantillas
            button: 'qq-upload-button',
            drop: 'qq-upload-drop-area',
            dropActive: 'qq-upload-drop-area-active',
            list: 'qq-upload-list',

            file: 'qq-upload-file',
            spinner: 'qq-upload-spinner',
            size: 'qq-upload-size',
            cancel: 'qq-upload-cancel',

            // añadido a la lista artículo cuando carga completa
            // utilizado en CSS para ocultar spinner progreso
            success: 'qq-upload-success',
            fail: 'qq-upload-fail'
        }
    });
    // sobrescribir las opciones del usuario suministradas
    qq.extend(this._options, o);

    this._element = this._options.element;
    this._element.innerHTML = this._options.template;
    this._listElement = this._options.listElement || this._find(this._element, 'list');

    this._classes = this._options.classes;

    this._button = this._createUploadButton(this._find(this._element, 'button'));

    this._bindCancelEvent();
    this._setupDragDrop();
};

// heredar de envío básica
qq.extend(qq.FileUploader.prototype, qq.FileUploaderBasic.prototype);

qq.extend(qq.FileUploader.prototype, {
    /**
     * Obtiene uno de los elementos enumerados en this._options.classes
     **/
    _find: function(parent, type)
    {
        var element = qq.getByClass(parent, this._options.classes[type])[0];
        if (!element)
        {
            throw new Error('element not found ' + type);
        }

        return element;
    },
    _setupDragDrop: function()
    {
        var self = this,
            dropArea = this._find(this._element, 'drop');

        var dz = new qq.UploadDropZone({
            element: dropArea,
            onEnter: function(e){
                qq.addClass(dropArea, self._classes.dropActive);
                e.stopPropagation();
            },
            onLeave: function(e){
                e.stopPropagation();
            },
            onLeaveNotDescendants: function(e){
                qq.removeClass(dropArea, self._classes.dropActive);
            },
            onDrop: function(e){
                dropArea.style.display = 'none';
                qq.removeClass(dropArea, self._classes.dropActive);
                self._uploadFileList(e.dataTransfer.files);
            }
        });

        dropArea.style.display = 'none';

        qq.attach(document, 'dragenter', function(e)
        {
            if (!dz._isValidFileDrag(e)) return;

            dropArea.style.display = 'block';
        });
        qq.attach(document, 'dragleave', function(e)
        {
            if(!dz._isValidFileDrag(e)) return;

            var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);
            // sólo disparar al salir documento fuera
            if( ! relatedTarget || relatedTarget.nodeName == "HTML")
            {
                dropArea.style.display = 'none';
            }
        });
    },
    _onSubmit: function(id, fileName)
    {
        qq.FileUploaderBasic.prototype._onSubmit.apply(this, arguments);
        this._addToList(id, fileName);
    },
    _onProgress: function(id, fileName, loaded, total)
    {
        qq.FileUploaderBasic.prototype._onProgress.apply(this, arguments);

        var item = this._getItemByFileId(id);
        var size = this._find(item, 'size');
        size.style.display = 'inline';

        var text;
        if(loaded != total)
        {
            text = Math.round(loaded / total * 100) + '% from ' + this._formatSize(total);
        }
        else
        {
            text = this._formatSize(total);
        }

        qq.setText(size, text);
    },
    _onComplete: function(id, fileName, result)
    {
        qq.FileUploaderBasic.prototype._onComplete.apply(this, arguments);

        // marcar como completada
        var item = this._getItemByFileId(id);
        qq.remove(this._find(item, 'cancel'));
        qq.remove(this._find(item, 'spinner'));

        if(result.success)
        {
            qq.addClass(item, this._classes.success);
        }
        else
        {
            qq.addClass(item, this._classes.fail);
        }
    },
    _addToList: function(id, fileName)
    {
        var item = qq.toElement(this._options.fileTemplate);
        item.qqFileId = id;

        var fileElement = this._find(item, 'file');
        qq.setText(fileElement, this._formatFileName(fileName));
        this._find(item, 'size').style.display = 'none';

        this._listElement.appendChild(item);
    },
    _getItemByFileId: function(id)
    {
        var item = this._listElement.firstChild;

        // no puede haber nodos txt en la lista creada dinámicamente
        // y podemos usar nextSibling
        while (item)
        {
            if (item.qqFileId == id) return item;
            item = item.nextSibling;
        }
    },
    /**
     * delegar evento click para el enlace de cancelación
     **/
    _bindCancelEvent: function()
    {
        var self = this,
            list = this._listElement;

        qq.attach(list, 'click', function(e)
        {
            e = e || window.event;
            var target = e.target || e.srcElement;

            if (qq.hasClass(target, self._classes.cancel))
            {
                qq.preventDefault(e);

                var item = target.parentNode;
                self._handler.cancel(item.qqFileId);
                qq.remove(item);
            }
        });
    }
});

qq.UploadDropZone = function(o)
{
    this._options = {
        element: null,
        onEnter: function(e){},
        onLeave: function(e){},
        // No se dispara al salir elemento por descendientes revoloteando
        onLeaveNotDescendants: function(e){},
        onDrop: function(e){}
    };
    qq.extend(this._options, o);

    this._element = this._options.element;

    this._disableDropOutside();
    this._attachEvents();
};

qq.UploadDropZone.prototype = {
    _disableDropOutside: function(e)
    {
        // ejecute sólo una vez para todos los casos
        if(!qq.UploadDropZone.dropOutsideDisabled )
        {
            qq.attach(document, 'dragover', function(e)
            {
                if (e.dataTransfer)
                {
                    e.dataTransfer.dropEffect = 'none';
                    e.preventDefault();
                }
            });

            qq.UploadDropZone.dropOutsideDisabled = true;
        }
    },
    _attachEvents: function()
    {
        var self = this;

        qq.attach(self._element, 'dragover', function(e)
        {
            if (!self._isValidFileDrag(e)) return;

            var effect = e.dataTransfer.effectAllowed;
            if (effect == 'move' || effect == 'linkMove')
            {
                e.dataTransfer.dropEffect = 'move'; // for FF (only move allowed)
            }
            else
            {
                e.dataTransfer.dropEffect = 'copy'; // for Chrome
            }

            e.stopPropagation();
            e.preventDefault();
        });

        qq.attach(self._element, 'dragenter', function(e)
        {
            if (!self._isValidFileDrag(e)) return;

            self._options.onEnter(e);
        });

        qq.attach(self._element, 'dragleave', function(e)
        {
            if (!self._isValidFileDrag(e)) return;

            self._options.onLeave(e);

            var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);
            // no disparar cuando se mueve el ratón sobre un descendiente
            if (qq.contains(this, relatedTarget)) return;

            self._options.onLeaveNotDescendants(e);
        });

        qq.attach(self._element, 'drop', function(e)
        {
            if (!self._isValidFileDrag(e)) return;

            e.preventDefault();
            self._options.onDrop(e);
        });
    },
    _isValidFileDrag: function(e)
    {
        var dt = e.dataTransfer,
            // no marque dt.types.contains en webkit, porque bloquea safari 4
            isWebkit = navigator.userAgent.indexOf("AppleWebKit") > -1;

        // dt.effectAllowed es ninguno en Safari 5
        // cheque dt.types.contains es para firefox
        return dt && dt.effectAllowed != 'none' &&
            (dt.files || (!isWebkit && dt.types.contains && dt.types.contains('Files')));

    }
};

qq.UploadButton = function(o)
{
    this._options = {
        element: null,
        // si se define como true agrega varios atributos para archivos de entrada
        multiple: false,
        // atributo de nombre de archivo de entrada
        name: 'file',
        onChange: function(input){},
        hoverClass: 'qq-upload-button-hover',
        focusClass: 'qq-upload-button-focus'
    };

    qq.extend(this._options, o);

    this._element = this._options.element;

    // hacer que el botón recipiente adecuado para la entrada
    qq.css(this._element, {
        position: 'relative',
        overflow: 'hidden',
        // Asegúrese de que el botón de exploración se encuentra en el lado derecho
        // en Internet Explorer
        direction: 'ltr'
    });

    this._input = this._createInput();
};

qq.UploadButton.prototype = {
    /* vuelve elemento de entrada de archivo */
    getInput: function()
    {
        return this._input;
    },
    /* Limpia / recrea el archivo de entrada */
    reset: function()
    {
        if (this._input.parentNode)
        {
            qq.remove(this._input);
        }

        qq.removeClass(this._element, this._options.focusClass);
        this._input = this._createInput();
    },
    _createInput: function(){
        var input = document.createElement("input");

        if (this._options.multiple)
        {
            input.setAttribute("multiple", "multiple");
        }

        input.setAttribute("type", "file");
        input.setAttribute("name", this._options.name);

        qq.css(input, {
            position: 'absolute',
            // Opera sólo en 'buscar' botón
            // se puede hacer clic y se encuentra en
            // el lado derecho de la entrada
            right: 0,
            top: 0,
            fontFamily: 'Arial',
            // 4 personas informaron de esto, los valores máximos que trabajaban para ellos fueron 243, 236, 236, 118
            fontSize: '118px',
            margin: 0,
            padding: 0,
            cursor: 'pointer',
            opacity: 0
        });

        this._element.appendChild(input);

        var self = this;
        qq.attach(input, 'change', function()
        {
            self._options.onChange(input);
        });

        qq.attach(input, 'mouseover', function()
        {
            qq.addClass(self._element, self._options.hoverClass);
        });
        qq.attach(input, 'mouseout', function()
        {
            qq.removeClass(self._element, self._options.hoverClass);
        });
        qq.attach(input, 'focus', function()
        {
            qq.addClass(self._element, self._options.focusClass);
        });
        qq.attach(input, 'blur', function()
        {
            qq.removeClass(self._element, self._options.focusClass);
        });

        // IE y Opera, por desgracia tienen 2 posiciones de tabulación en la entrada del archivo
        // lo que es inaceptable en nuestro caso, deshabilite el acceso de teclado
        if (window.attachEvent)
        {
            // es IE u Opera
            input.setAttribute('tabIndex', "-1");
        }

        return input;
    }
};

/**
 * Clase para subir archivos, uploading misma está a cargo de las clases de los hijos
 */
qq.UploadHandlerAbstract = function(o)
{
    this._options = {
        debug: false,
        action: '/upload.php',
        // número máximo de cargas simultáneas
        maxConnections: 999,
        onProgress: function(id, fileName, loaded, total){},
        onComplete: function(id, fileName, response){},
        onCancel: function(id, fileName){}
    };
    qq.extend(this._options, o);

    this._queue = [];
    // params para archivos en cola
    this._params = [];
};
qq.UploadHandlerAbstract.prototype = {
    log: function(str)
    {
        if (this._options.debug && window.console) console.log('[uploader] ' + str);
    },
    /**
     * Incorpora entrada de archivo o un archivo a la cola
     * @returns id
     **/
    add: function(file){},
    /**
     * Envía el archivo identificado por id y params consulta adicionales en el servidor
     */
    upload: function(id, params)
    {
        var len = this._queue.push(id);

        var copy = {};
        qq.extend(copy, params);
        this._params[id] = copy;

        // si demasiados archivos activos, espera ...
        if(len <= this._options.maxConnections)
        {
            this._upload(id, this._params[id]);
        }
    },
    /**
     * Cancela el archivo subido por ID
     */
    cancel: function(id)
    {
        this._cancel(id);
        this._dequeue(id);
    },
    /**
     * Cancelar todos los archivos subidos
     */
    cancelAll: function()
    {
        for(var i=0; i<this._queue.length; i++)
        {
            this._cancel(this._queue[i]);
        }
        this._queue = [];
    },
    /**
     * Devuelve el nombre del archivo identificado por id
     */
    getName: function(id){},
    /**
     * Devuelve el tamaño del archivo identificado por id
     */
    getSize: function(id){},
    /**
     * Devuelve el id de los archivos que estan siendo subidos o esperando por subir
     */
    getQueue: function()
    {
        return this._queue;
    },
    /**
     * Método actual carga
     */
    _upload: function(id){},
    /**
     * Método Actual cancelacion
     */
    _cancel: function(id){},
    /**
     * Elimina el elemento de la cola, se inicia carga de la próxima
     */
    _dequeue: function(id)
    {
        var i = qq.indexOf(this._queue, id);
        this._queue.splice(i, 1);

        var max = this._options.maxConnections;

        if(this._queue.length >= max)
        {
            var nextId = this._queue[max-1];
            this._upload(nextId, this._params[nextId]);
        }
    }
};

/**
 * Clase para subir archivos a través del formulario y iframe
 * @inherits qq.UploadHandlerAbstract
 */
qq.UploadHandlerForm = function(o)
{
    qq.UploadHandlerAbstract.apply(this, arguments);

    this._inputs = {};
};
// @inherits qq.UploadHandlerAbstract
qq.extend(qq.UploadHandlerForm.prototype, qq.UploadHandlerAbstract.prototype);

qq.extend(qq.UploadHandlerForm.prototype, {
    add: function(fileInput)
    {
        fileInput.setAttribute('name', 'qqfile');
        var id = 'qq-upload-handler-iframe' + qq.getUniqueId();

        this._inputs[id] = fileInput;

        // eliminar el archivo de entrada de DOM
        if(fileInput.parentNode)
        {
            qq.remove(fileInput);
        }

        return id;
    },
    getName: function(id)
    {
        // obtener el valor de entrada y quite ruta para normalizar
        return this._inputs[id].value.replace(/.*(\/|\\)/, "");
    },
    _cancel: function(id)
    {
        this._options.onCancel(id, this.getName(id));

        delete this._inputs[id];

        var iframe = document.getElementById(id);
        if (iframe)
        {
            /*
            * para cancelar la solicitud src establece en algo más que utilizamos 
            * src = "javascript: false," porque no se activa ie6 sistema en https
            */
            iframe.setAttribute('src', 'javascript:false;');

            qq.remove(iframe);
        }
    },
    _upload: function(id, params)
    {
        var input = this._inputs[id];

        if(!input)
        {
            throw new Error('no se agregó archivo con id pasado, o subido o cancelada');
        }

        var fileName = this.getName(id);

        var iframe = this._createIframe(id);
        var form = this._createForm(iframe, params);
        form.appendChild(input);

        var self = this;
        this._attachLoadEvent(iframe, function()
        {
            self.log('iframe loaded');

            var response = self._getIframeContentJSON(iframe);

            self._options.onComplete(id, fileName, response);
            self._dequeue(id);

            delete self._inputs[id];
            // timeout añadido para arreglar estado ocupado en FF3.6
            setTimeout(function()
            {
                qq.remove(iframe);
            }, 1);
        });

        form.submit();
        qq.remove(form);

        return id;
    },
    _attachLoadEvent: function(iframe, callback)
    {
        qq.attach(iframe, 'load', function()
        {
            /*
            * cuando quitamos iframe de libertad las paradas petición, pero activa el evento de carga IE
            */
            if (!iframe.parentNode)
            {
                return;
            }

            // la fijación de Opera 10.53
            if (iframe.contentDocument &&
                iframe.contentDocument.body &&
                iframe.contentDocument.body.innerHTML == "false")
            {
                /*
                * En caso de Opera se dispara segunda vez cuando body.innerHTML 
                * cambia de falso a un servidor de respuesta aprox. 
                * después de 1 segundo cada vez que pongamos archivo con iframe
                */
                return;
            }

            callback();
        });
    },
    /**
     * Devoluciones json objeto recibe iframe desde un servidor.
     */
    _getIframeContentJSON: function(iframe)
    {
        // iframe.contentWindow.document - para IE <7
        var doc = iframe.contentDocument ? iframe.contentDocument: iframe.contentWindow.document,
            response;

        this.log("converting iframe's innerHTML to JSON");
        this.log("innerHTML = " + doc.body.innerHTML);

        try
        {
            response = eval("(" + doc.body.innerHTML + ")");
        }
        catch(err)
        {
            response = {};
        }

        return response;
    },
    /**
     * Crea iframe con nombre único
     */
    _createIframe: function(id)
    {
        /*
        * No podemos utilizar el código siguiente del atributo name no se 
        * registrará correctamente en IE6, y una nueva ventana en la forma presente, 
        * se abrirá
        * var iframe = document.createElement ('iframe');
        * iframe.setAttribute ('nombre', id);
        */

        var iframe = qq.toElement('<iframe src="javascript:false;" name="' + id + '" />');
        // src="javascript:false;" elimina ie6 sistema en https

        iframe.setAttribute('id', id);

        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        return iframe;
    },
    /**
     * Crea formulario, que será presentado al iframe
     */
    _createForm: function(iframe, params)
    {
        /*
        * No podemos usar el siguiente código en IE6
        * var form = document.createElement ('form');
        * form.setAttribute ('método', 'post');
        * form.setAttribute ('enctype', 'multipart / form-data ");
        * Debido a que en este caso no se adjuntará el archivo de solicitar
        */
        var form = qq.toElement('<form method="post" enctype="multipart/form-data"></form>');

        var queryString = qq.obj2url(params, this._options.action);

        form.setAttribute('action', queryString);
        form.setAttribute('target', iframe.name);
        form.style.display = 'none';
        document.body.appendChild(form);

        return form;
    }
});

/**
 * Clase para subir archivos mediante XHR
 * @inherits qq.UploadHandlerAbstract
 */
qq.UploadHandlerXhr = function(o)
{
    qq.UploadHandlerAbstract.apply(this, arguments);

    this._files = [];
    this._xhrs = [];

    // tamaño actual cargado en bytes de cada archivo
    this._loaded = [];
};

// método estático
qq.UploadHandlerXhr.isSupported = function()
{
    var input = document.createElement('input');
    input.type = 'file';

    return (
        'multiple' in input &&
        typeof File != "undefined" &&
        typeof (new XMLHttpRequest()).upload != "undefined" );
};

// @inherits qq.UploadHandlerAbstract
qq.extend(qq.UploadHandlerXhr.prototype, qq.UploadHandlerAbstract.prototype)

qq.extend(qq.UploadHandlerXhr.prototype, {
    /**
    * Añade archivos a la cola
    * Devuelve ID a utilizar con carga, cancele
    */
    add: function(file)
    {
        if(!(file instanceof File))
        {
            throw new Error('Aprobada en obj no es un archivo (in qq.UploadHandlerXhr)');
        }

        return this._files.push(file) - 1;
    },
    getName: function(id)
    {
        var file = this._files[id];
        // fijar falta el nombre de Safari 4
        return file.fileName != null ? file.fileName : file.name;
    },
    getSize: function(id)
    {
        var file = this._files[id];
        return file.fileSize != null ? file.fileSize : file.size;
    },
    /**
     * Devoluciones subidos bytes de archivo identificado por id
     */
    getLoaded: function(id)
    {
        return this._loaded[id] || 0;
    },
    /**
     * Envía el archivo identificado por id y params consulta adicionales en el servidor
     * @param {Object} params nombre-valor pares de cadenas
     */
    _upload: function(id, params)
    {
        var file = this._files[id],
            name = this.getName(id),
            size = this.getSize(id);

        this._loaded[id] = 0;

        var xhr = this._xhrs[id] = new XMLHttpRequest();
        var self = this;

        xhr.upload.onprogress = function(e)
        {
            if(e.lengthComputable)
            {
                self._loaded[id] = e.loaded;
                self._options.onProgress(id, name, e.loaded, e.total);
            }
        };

        xhr.onreadystatechange = function()
        {
            if(xhr.readyState == 4)
            {
                self._onComplete(id, xhr);
            }
        };

        // construir una cadena de consulta
        params = params || {};
        params['qqfile'] = name;
        var queryString = qq.obj2url(params, this._options.action);

        xhr.open("POST", queryString, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("X-File-Name", encodeURIComponent(name));
        xhr.setRequestHeader("Content-Type", "application/octet-stream");
        xhr.send(file);
    },
    _onComplete: function(id, xhr)
    {
        // la solicitud fue abortado / cancelado
        if (!this._files[id]) return;

        var name = this.getName(id);
        var size = this.getSize(id);

        this._options.onProgress(id, name, size, size);

        if(xhr.status == 200)
        {
            this.log("xhr - server response received");
            this.log("responseText = " + xhr.responseText);

            var response;

            try
            {
                response = eval("(" + xhr.responseText + ")");
            }
            catch(err)
            {
                response = {};
            }

            this._options.onComplete(id, name, response);

        }
        else
        {
            this._options.onComplete(id, name, {});
        }

        this._files[id] = null;
        this._xhrs[id] = null;
        this._dequeue(id);
    },
    _cancel: function(id)
    {
        this._options.onCancel(id, this.getName(id));

        this._files[id] = null;

        if(this._xhrs[id])
        {
            this._xhrs[id].abort();
            this._xhrs[id] = null;
        }
    }
});