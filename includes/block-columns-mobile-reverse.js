(function(wp) {
    const { registerPlugin } = wp.plugins;
    const { Fragment, createElement } = wp.element;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, CheckboxControl } = wp.components;

    const VisibilityControls = wp.compose.createHigherOrderComponent((BlockEdit) => {
        return function(props) {
            const { attributes, setAttributes } = props;

            // Only apply to block columns
            var addFields = false;
            
            if (
                props.name == 'core/columns' ||
                props.name == 'core/media-text'
            ) {
                addFields = true;
            }
            
            if(!addFields) {
                return createElement(BlockEdit, props);
            }

            const visibilityClasses = [
                'mobile-reverse'
            ];

            const updateVisibilityClass = (className, isChecked) => {
                const currentClasses = new Set((attributes.className || '').split(' ').filter(Boolean));
                if (isChecked) {
                    currentClasses.add(className);
                } else {
                    currentClasses.delete(className);
                }
                setAttributes({ className: Array.from(currentClasses).join(' ') });
            };

            return createElement(
                Fragment,
                null,
                createElement(BlockEdit, props),
                createElement(
                    InspectorControls,
                    null,
                    createElement(
                        PanelBody,
                        { title: "Columns Options" },
                        visibilityClasses.map((className) => {
                            return createElement(CheckboxControl, {
                                key: className,
                                label: className.replace('-', ' ').replace(/\b\w/g, (char) => char.toUpperCase()),
                                checked: (attributes.className || '').split(' ').includes(className),
                                onChange: (isChecked) => updateVisibilityClass(className, isChecked)
                            });
                        })
                    )
                )
            );
        };
    }, 'VisibilityControls');

    wp.hooks.addFilter('editor.BlockEdit', 'block-visibility-options/visibility-controls', VisibilityControls);
})(window.wp);
