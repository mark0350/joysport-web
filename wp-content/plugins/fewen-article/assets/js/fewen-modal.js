(function () {
    
    this.FewenModal = function () {

        this.modal = null;
        this.overlay = null;

        var defaults = {
            content : '',
            closeButton: true,
            overlay: true
        }

        if( arguments[0] && 'object' === typeof arguments[0]){
            this.options = mergeDefault(defaults, arguments[0])
        }


    }

    FewenModal.prototype.open = function () {
        buildout.call(this);

        initEvents.call(this);

        adjustHeight.call(this);

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

        var content, contentHolder, docFrag;

        if(typeof this.options.content === 'string'){
            content = this.options.content;
        } else {
            content = this.options.content.innerHTML;
        }

        docFrag = Document.createDocumentFragment();

        this.modal = Document.createElement('div');
        this.modal.className = "scotch-modal " + this.options.className;
        this.modal.style.minWidth = this.options.minWidth + "px";
        this.modal.style.maxWidth = this.options.maxWidth + "px";

        // If closeButton option is true, add a close button
        if (this.options.closeButton === true) {
            this.closeButton = document.createElement("button");
            this.closeButton.className = "scotch-close close-button";
            this.closeButton.innerHTML = "×";
            this.modal.appendChild(this.closeButton);
        }

        // If overlay is true, add one
        if (this.options.overlay === true) {
            this.overlay = document.createElement("div");
            this.overlay.className = "scotch-overlay " + this.options.classname;
            docFrag.appendChild(this.overlay);
        }

    }

    function initEvents() {

    }

    function adjustHeight() {

    }
    
    
}())