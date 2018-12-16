(function ($) {

    window.PC_MULTIPLE_IMAGES = {

        init(){
            this.$openMediaBtn = $('#pc-multiple-images-select-media');
            this.$clearMediaBtn = $('#pc-multiple-images-clear-media');
            this.$previewContainer = $('#pc-multiple-images-preview');
            this.$imagesInput = $('#pc-multiple-images-input');
            this.$postFormatRadio = $('input[name=post_format]');
            this.$postFormatRadioGallery = $('#post-format-gallery');
            this.$metabox = $('#pc-multiple-images-metabox');
            this.mediaFrame = null;
            this._createMediaFrame();
            this._assignEvents();
            this._initializeMetabox();
        },

        _initializeMetabox() {
            if (this.$metabox.length) {
                this.$metabox.hide();
                if (this.$postFormatRadioGallery[0].checked) {
                    this.$metabox.show();
                }
            }
        },

        _assignEvents(){
            this.$openMediaBtn.on('click', this._clickMediaBtnHandler.bind(this));
            if (this.mediaFrame) {
                this.mediaFrame.on('select', this._mediaFrameOnSelectHandler.bind(this));
            }
            this.$clearMediaBtn.on('click', this._clickClearMediaBtnHandler.bind(this));
            this.$postFormatRadio.on('change', this._postFormatRadioChangeHandler.bind(this));
        },

        _postFormatRadioChangeHandler(e){
            const target = e.target;
            if (target.id === 'post-format-gallery') {
                this.$metabox.show();
            } else {
                this.$metabox.hide();
            }
        },

        _clickMediaBtnHandler(e){
            e.preventDefault();
            if(this.mediaFrame) {
                this.mediaFrame.open();
            }
        },

        _createMediaFrame(){
            if (!wp.media) return;
            this.mediaFrame = wp.media({
                title: 'Wybierz dodatkowe zdjęcia',
                button: {
                    text: 'Wstaw zdjęcia'
                },
                multiple: true
            });
        },

        _mediaFrameOnSelectHandler(){
            const images = this.mediaFrame.state().get('selection').toJSON();
            this.$imagesInput.val(JSON.stringify(images));
            this.$previewContainer.empty();
            images.forEach(img => {
                const $item = $('<a class="pc-multiple-images-preview-single" href="#"><img src="'+ img.sizes.thumbnail.url +'" ></a>');
                this.$previewContainer.append($item);
            });
            this.$openMediaBtn.hide();
            this.$clearMediaBtn.show();
        },

        _clickClearMediaBtnHandler(e){
            e.preventDefault();
            this.$imagesInput.val('');
            this.$previewContainer.empty();
            this.$clearMediaBtn.hide();
            this.$openMediaBtn.show();
        }

    };

    PC_MULTIPLE_IMAGES.init();

})(jQuery);