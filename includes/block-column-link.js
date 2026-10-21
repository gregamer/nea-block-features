(function (wp) {
    var addFilter = wp.hooks.addFilter;
    var createElement = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var useMemo = wp.element.useMemo;
    var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var LinkControl = wp.blockEditor.LinkControl || wp.blockEditor.__experimentalLinkControl;
    var PanelBody = wp.components.PanelBody;
    var ToggleControl = wp.components.ToggleControl;
    var TextControl = wp.components.TextControl;
    var Notice = wp.components.Notice;
    var __ = wp.i18n.__;

    function emptyLink() {
        return {
            url: '',
            title: '',
            opensInNewTab: false,
            nofollow: false
        };
    }

    function addColumnLinkAttribute(settings, name) {
        if (name !== 'core/column') {
            return settings;
        }

        settings.attributes = Object.assign({}, settings.attributes, {
            neaColumnLink: {
                type: 'object',
                default: emptyLink()
            }
        });

        return settings;
    }

    addFilter(
        'blocks.registerBlockType',
        'nea-block-features/column-link-attributes',
        addColumnLinkAttribute
    );

    var existingColumn = wp.blocks.getBlockType('core/column');
    if (existingColumn && !existingColumn.attributes.neaColumnLink) {
        existingColumn.attributes.neaColumnLink = {
            type: 'object',
            default: emptyLink()
        };
    }

    var withColumnLinkControls = createHigherOrderComponent(function (BlockEdit) {
        return function (props) {
            if (props.name !== 'core/column') {
                return createElement(BlockEdit, props);
            }

            var link = props.attributes.neaColumnLink || emptyLink();
            var hasUrl = typeof link.url === 'string' && link.url !== '';

            var linkValue = useMemo(function () {
                var value = {
                    url: link.url || '',
                    title: link.title || ''
                };

                if (link.id) {
                    value.id = link.id;
                }
                if (link.kind) {
                    value.kind = link.kind;
                }
                if (link.type) {
                    value.type = link.type;
                }

                return value;
            }, [link.url, link.title, link.id, link.kind, link.type]);

            function storeLink(next) {
                var stored = {
                    url: (next && next.url) || '',
                    title: (next && next.title) || '',
                    opensInNewTab: !!link.opensInNewTab,
                    nofollow: !!link.nofollow
                };

                if (next && next.id) {
                    stored.id = next.id;
                }
                if (next && next.kind) {
                    stored.kind = next.kind;
                }
                if (next && next.type) {
                    stored.type = next.type;
                }

                props.setAttributes({ neaColumnLink: stored });
            }

            var urlControl = LinkControl
                ? createElement(LinkControl, {
                    key: props.clientId,
                    value: hasUrl ? linkValue : undefined,
                    searchInputPlaceholder: __('Išči stran ali vnesi URL', 'nea_block_features'),
                    settings: [],
                    onChange: storeLink,
                    onRemove: function () {
                        props.setAttributes({ neaColumnLink: emptyLink() });
                    }
                })
                : createElement(TextControl, {
                    label: __('URL povezave', 'nea_block_features'),
                    value: link.url || '',
                    onChange: function (url) {
                        storeLink({ url: url, title: link.title || '' });
                    }
                });

            return createElement(
                Fragment,
                null,
                createElement(BlockEdit, props),
                createElement(
                    InspectorControls,
                    null,
                    createElement(
                        PanelBody,
                        {
                            title: __('Povezava stolpca', 'nea_block_features'),
                            initialOpen: hasUrl
                        },
                        hasUrl
                            ? createElement(
                                Notice,
                                { status: 'info', isDismissible: false },
                                __('Stolpec ima nastavljeno povezavo.', 'nea_block_features')
                            )
                            : null,
                        createElement(
                            'p',
                            { className: 'components-base-control__help' },
                            __('Na spletnem mestu klik na prosto površino stolpca odpre povezavo. Povezave in gumbi znotraj stolpca ostanejo samostojni. V urejevalniku je stolpec še naprej urejljiv.', 'nea_block_features')
                        ),
                        urlControl,
                        createElement(ToggleControl, {
                            label: __('Odpri v novem zavihku', 'nea_block_features'),
                            help: __('Doda target="_blank" in rel="noopener noreferrer".', 'nea_block_features'),
                            checked: !!link.opensInNewTab,
                            onChange: function (checked) {
                                props.setAttributes({
                                    neaColumnLink: Object.assign({}, emptyLink(), link, { opensInNewTab: checked })
                                });
                            }
                        }),
                        createElement(ToggleControl, {
                            label: __('Nofollow', 'nea_block_features'),
                            help: __('Doda nofollow v atribut rel.', 'nea_block_features'),
                            checked: !!link.nofollow,
                            onChange: function (checked) {
                                props.setAttributes({
                                    neaColumnLink: Object.assign({}, emptyLink(), link, { nofollow: checked })
                                });
                            }
                        })
                    )
                )
            );
        };
    }, 'withNeaColumnLinkControls');

    addFilter(
        'editor.BlockEdit',
        'nea-block-features/column-link-controls',
        withColumnLinkControls
    );
})(window.wp);
