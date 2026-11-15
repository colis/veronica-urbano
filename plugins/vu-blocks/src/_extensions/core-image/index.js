import { registerBlockExtension } from '@10up/block-components';
import MobileImage, {
	mobileImageAttributes,
} from '../../utils/components/MobileImage';

/**
 * Additional block attributes object.
 */
const additionalAttributes = {
	...mobileImageAttributes,
};

/**
 * BlockEdit
 *
 * @param {Object} props Block props.
 * @return {HTMLElement} The new controls.
 */
const BlockEdit = (props) => {
	return (
		<>
			<MobileImage {...props} />
		</>
	);
};

/**
 * registerBlockExtension
 *
 * A function to register the block extension.
 */
registerBlockExtension(['core/image'], {
	extensionName: 'core-image-ext',
	attributes: additionalAttributes,
	classNameGenerator: () => null,
	inlineStyleGenerator: () => null,
	Edit: BlockEdit,
});
