(function () {
    
    this.FewenModal = function () {

        this.closeButton = null;
        this.modal = null;
        this.overlay = null;

        this.transitionEnd = transitionSelect();


        var defaults = {
            className: 'fade-and-drop',
            closeButton: true,
            content: "",
            maxWidth: 600,
            minWidth: 280,
            overlay: true,
            title:false,
            onOpen: false
        };

        if( arguments[0] && 'object' === typeof arguments[0]){
            this.options = mergeDefault(defaults, arguments[0])
        }


    }

    FewenModal.prototype.open = function () {
        buildout.call(this);

        initEvents.call(this);

        window.getComputedStyle(this.modal).height;
        this.modal.className = this.modal.className +
            (this.modal.offsetHeight > window.innerHeight ?
                " scotch-open scotch-anchored" : " scotch-open");
        this.overlay.className = this.overlay.className + " scotch-open";

        if('function' === typeof this.options.onOpen){
            this.options.onOpen();
        }

    }

    function mergeDefault(source, properties) {
        var property;
        for(property in properties){
            if(source.hasOwnProperty(property)){
                source[property] = properties[property]
            }
        }

        return source;
    }

    function buildout() {

        var content, contentHolder, docFrag, title, titleHolder;

        if(typeof this.options.content === 'string'){
            content = this.options.content;
        } else {
            content = this.options.content.innerHTML;
        }

        docFrag = document.createDocumentFragment();

        this.modal = document.createElement('div');
        this.modal.className = "scotch-modal " + this.options.className;
        this.modal.style.minWidth = this.options.minWidth + "px";
        this.modal.style.maxWidth = this.options.maxWidth + "px";


        // If closeButton option is true, add a close button
        if (this.options.closeButton === true) {
            this.closeButton = document.createElement("button");
            this.closeButton.className = "scotch-close close-button";
            this.closeButton.innerHTML = "×";
        }

        // If overlay is true, add one
        if (this.options.overlay === true) {
            this.overlay = document.createElement("div");
            this.overlay.className = "scotch-overlay " + this.options.classname;
            docFrag.appendChild(this.overlay);
        }

        if(this.options.title){
            titleHolder = document.createElement("div");
            titleHolder.className = "scotch-title";
            titleHolder.innerHTML = this.options.title;
            if(this.closeButton){
                titleHolder.appendChild(this.closeButton)
            }
            this.modal.appendChild(titleHolder);
        }else{
            if(this.closeButton){
                this.modal.appendChild(this.closeButton);
            }
        }



        // Create content area and append to modal
        contentHolder = document.createElement("div");
        contentHolder.className = "scotch-content";
        contentHolder.innerHTML = content;
        this.modal.appendChild(contentHolder);



        // Append modal to DocumentFragment
        docFrag.appendChild(this.modal);



        // Append DocumentFragment to body
        document.body.appendChild(docFrag);





    }

    function initEvents() {
        if(this.closeButton){
            this.closeButton.addEventListener('click', this.close.bind(this))
        }
        if(this.overlay){
            this.overlay.addEventListener('click', this.close.bind(this))
        }
    }


    // Public Methods

    FewenModal.prototype.close = function() {
        var _ = this;
        this.modal.className = this.modal.className.replace(" scotch-open", "");
        this.overlay.className = this.overlay.className.replace(" scotch-open",
            "");
        this.modal.addEventListener(this.transitionEnd, function() {
            _.modal.parentNode.removeChild(_.modal);
        });
        this.overlay.addEventListener(this.transitionEnd, function() {
            if(_.overlay.parentNode) _.overlay.parentNode.removeChild(_.overlay);
        });
    }

    function transitionSelect() {
        var el = document.createElement("div");
        if (el.style.WebkitTransition) return "webkitTransitionEnd";
        if (el.style.OTransition) return "oTransitionEnd";
        return 'transitionend';
    }
    
    
}())