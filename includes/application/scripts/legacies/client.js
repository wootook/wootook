/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */
/**
 * Main application script execution class
 *
 * @param string responder
 * @param int messageOffset
 */

function Legacies_Client(responder, messageOffset) {
    if (!document.querySelectorAll) {
        alert("Your browser is not compatible with CSS selectors API.\n\nCould not continue.");
        return;
    }
    this.responder = responder;
    this.messages = [];
    this.messageId = messageOffset || 1;
    this.callbacks = {};
    this.recurrentMessages = [];

    this.send = (function() {
        if (this.messages.length == 0) {
            return this;
        }
        var request = new XMLHttpRequest();
        request.open('POST', this.responder, false);
        request.send(JSON.stringify(this.messages));
        this.messages = [];
        for (var i in this.recurrentMessages) {
            this.enqueue(this.recurrentMessages[i].action, this.recurrentMessages[i].params, this.recurrentMessages[i].callback);
        }
        if(request.status == 404) {
            alert('Request failed, responder not found.');
            return this;
        }
        try {
            var response = JSON.parse(request.responseText);
        } catch (e) {
            alert('Invalid response.');
            return this;
        }
        if(request.status == 200) {
            for (var i in response) {
                this._callback(response[i]);
            }
        }
        return this;
    }).bind(this);

    this.main = (function(interval) {
        setInterval(this.send, interval || 30000);
    }).bind(this);

    this.enqueue = (function(action, params){
        this.messages.push({'uid':this.messageId++,'action':action,'params':params});
        return this;
    }).bind(this);

    this.enqueueRecurrent = (function(action, params, callback) {
        this.recurrentMessages.push({'callback':callback,'action':action,'params':params});
        this.enqueue(action, params, callback);
    }).bind(this);

    this._callback = (function(message){
        if (message.error === undefined || message.payload === undefined) {
            return this;
        }
        if (message.error === true) {
            if (typeof(message.payload.messages) == 'string') {
                alert(message.payload.messages);
            } else {
                var info = '';
                for (var i in message.payload.messages) {
                    info = info + "\n[" + i + "]:\n" + message.payload.messages[i].join("\n") + "\n";
                }
                alert("Errors were returned:\n" + info);
            }
            return this;
        }
        if (message.callback && this.CallbackType[message.callback]) {
            (this.CallbackType[message.callback]).bind(this)(message.payload);
        }
        return this;
    }).bind(this);

    this.login = (function(username, password, rememberme) {
        var params = {
            'username':   username,
            'password':   password,
            'rememberme': rememberme || false
            };
        this.enqueue('user.account.login', params).send();
        return this;
    }).bind(this);

    this.logout = (function() {
        this.enqueue('user.account.logout', {}).send();
        return this;
    }).bind(this);

    this.form = (function(event) {
        event.preventDefault();
        var form = event.target;
        var params = {};
        for (var i = 0; i < form.length; i++) {
            if (!form[i].name || !form[i].type) {
                continue;
            }
            if (form[i].type == 'checkbox') {
                if (form[i].checked) {
                    params[form[i].name] = true;
                } else {
                    params[form[i].name] = false;
                }
            } else if (form[i].type == 'radio') {
                params[form[i].name] = form[i].value;
            } else if (form[i].value !== undefined) {
                params[form[i].name] = form[i].value;
            }
        }
        var action = form.action.slice(form.action.indexOf('#') + 1);
        this.enqueue(action, params, this.CallbackType.updater).send();
    }).bind(this);

    this.CallbackType = {
        redirector: function(payload) {
            if (payload.redirect) {
                document.location.href = payload.redirect;
            }
        },
        updater: function(payload) {
            if (payload.html && payload.selector) {
                var parent = $$(payload.selector);
                var handler = document.createElement("div");
                handler.innerHTML = payload.html;

                var fragment = document.createDocumentFragment();
                for (var i = 0; i < handler.childNodes.length; i++) {
                    fragment.appendChild(handler.childNodes[i]);
                }

                for (var i = 0; i < parent.length; i++) {
                    parent[i].appendChild(fragment.cloneNode(true));
                } 
            }
        },
        script: function(payload) {
            if (payload.script) {
                eval(payload.script);
            }
        },
        error: function(payload) {
            if (payload.messages) {
                alert(payload.messages.join(', '));
            }
        },
        show: function(payload) {
            if (payload.selector) {
                var elementList = $$(payload.selector);
                for (var i = 0; i < elementList.length; i++) {
                    elementList[i].style.display = 'block';
                }
            }
        },
        hide: function(payload) {
            if (payload.selector) {
                var elementList = $$(payload.selector);
                for (var i = 0; i < elementList.length; i++) {
                    elementList[i].style.display = 'none';
                }
            }
        },
        callback: function(payload) {
            if (payload.callback) {
                payload.callback.apply(this, payload.params);
            }
        },
        enqueueRecurrent: function(payload) {
            if (payload.action && payload.params && payload.callback) {
                this.enqueueRecurrent(payload.action, payload.params, payload.callback);
            }
        }
        };
}

function Legacies_Element(object) {
    object.listen = (function(event, callback) {
        this.addEventListener(event, callback, false);
        return this;
    }).bind(object);

    return object;
}

function Legacies_List(object) {
    object.listen = (function(event, callback) {
        for (var i in this) {
            Legacies_Element(Legacies_Element[i]);
            this[i].listen(event, callback);
        }
        return this;
    }).bind(object);

    return object;
}

function $A(object) {
    return Array.prototype.slice.call(object);
}

function $(element) {
    if (typeof(element) == 'string') {
        element = document.getElementById(element);
    }
    Legacies_Element(element);
    return element;
}

function $$(selector, context) {
    context = context || document;
    var nodeList = context.querySelectorAll(selector);
    Legacies_List(nodeList);
    return nodeList;
}

function $E(element, methods) {
    for (var i in methods) {
        if (methods[i] === undefined) {
            continue;
        }
        element[i] = function(){
            var args = $A(arguments);
            args.unshift(element);
            methods[i].apply(element, args);
            }
        element[i].bind(element);
    }
    return element;
}