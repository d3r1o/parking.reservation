class WvEdit {
    constructor(p) {
        this.componentName = p.componentName;
        this.id = p.id;
        this.client = p.client;
        this.init();
    }

    init() {
        const selectItem = () => {
            let selectedItems = tagSelector.getDialog().getSelectedItems();

            let result = {};

            if (BX.type.isArray(selectedItems))
            {
                selectedItems.forEach((item) => {
                    result['entityId'] = item.entityId;
                    result['id'] = item.id;
                });
            }

            $('[name="CLIENT"]').val(JSON.stringify(result));
        };

        let preselectedItems = [];

        if (this.client) {
            preselectedItems = [[this.client.entityId, this.client.id]];

            $('[name="CLIENT"]').val(JSON.stringify(this.client));
        }

        const tagSelector = new BX.UI.EntitySelector.TagSelector({
            id: 'clientField',
            multiple: false,
            dialogOptions: {
                preselectedItems: preselectedItems,
                context: 'wv_edit',
                entities: [
                    {
                        id: 'lead',
                        dynamicLoad: true,
                        dynamicSearch: true
                    },
                    {
                        id: 'deal',
                        dynamicLoad: true,
                        dynamicSearch: true
                    },
                ],
                events: {
                    'Item:onSelect': selectItem,
                    'Item:onDeselect': selectItem,
                }
            },
        });

        tagSelector.renderTo(document.getElementById('clientField'));
    }

    editAction() {
        let app = this;

        let slider = BX.SidePanel.Instance.getTopSlider();

        BX.ajax.runComponentAction(
            `${app.componentName}`,
            'update',
            {
                mode: 'class',
                data: {
                    params: app.collectFormData()
                },
            }
        ).then(function () {
            slider.close();
        }, function (e) {
            console.log(e);
            slider.close();
        });
    }

    removeAction()
    {
        let app = this;

        let slider = BX.SidePanel.Instance.getTopSlider();

        BX.ajax.runComponentAction(
            `${app.componentName}`,
            'remove',
            {
                mode: 'class',
                data: {
                    id: app.id
                },
            }
        ).then(function () {
            slider.close();
        }, function (e) {
            console.log(e);
            slider.close();
        });
    }

     collectFormData() {
         let formData = {};

         $('.ui-ctl-element').each(function() {
             if (this.name) {
                 formData[this.name] = $(this).val();
             }
         });

         return formData;
     }
}