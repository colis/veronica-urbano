import { registerBlockExtension } from '@10up/block-components';
import MobileImage, {
	mobileImageAttributes,
} from '../../utils/components/MobileImage';

/**
 * BlockEdit
 *
 * @param {Object} props Block props.
 * @return {HTMLElement} The new controls.
 */
const BlockEdit = (props) => {
	return <MobileImage {...props} />;
};

/**
 * registerBlockExtension
 *
 * A function to register the block extension.
 */
registerBlockExtension(['core/cover'], {
	extensionName: 'core-cover-ext',
	attributes: mobileImageAttributes,
	classNameGenerator: () => null,
	inlineStyleGenerator: () => null,
	Edit: BlockEdit,
});
