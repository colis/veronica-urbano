import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import edit from './edit';
import './index.css';
import './style.css';

registerBlockType(metadata, {
	edit,
});
