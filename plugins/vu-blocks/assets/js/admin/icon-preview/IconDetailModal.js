/**
 * Icon Detail Modal
 *
 * Shows the selected icon in a design system reference view:
 * - All sizes on a neutral background
 * - Color matrix (background × foreground)
 * - Sizes × colors showcase
 */
import { Modal } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import { isLightColor } from '../../../../src/utils/functions/color';

/**
 * Icon brand color tokens.
 */
const ICON_COLORS = [
	{ name: __('White', 'vu-blocks'), value: '#FFFFFF' },
	{ name: __('Brand Red', 'vu-blocks'), value: '#DD0031' },
	{ name: __('Brand Blue', 'vu-blocks'), value: '#004F71' },
	{ name: __('Cream', 'vu-blocks'), value: '#FCF9F4' },
	{ name: __('Cool White', 'vu-blocks'), value: '#F4F8F9' },
];

/**
 * Icon sizes in pixels, generated in intervals of 8.
 */
const ICON_SIZE_MIN = 16;
const ICON_SIZE_MAX = 96;
const ICON_SIZES = Array.from(
	{ length: (ICON_SIZE_MAX - ICON_SIZE_MIN) / 8 + 1 },
	(_, i) => ICON_SIZE_MIN + i * 8
);

/**
 * Render an inline SVG with a given color, and a specified size.
 *
 * @param {Object} props           Props object.
 * @param {string} props.svgString The SVG string to render.
 * @param {string} props.color     The color to apply to the SVG.
 * @param {number} props.size      The size (width and height) of the SVG in pixels.
 *
 * @return {Element} The rendered colored icon component.
 */
function ColoredIcon({ svgString, color, size }) {
	const coloredSvg = svgString
		.replace(/fill="(?!none)[^"]*"/g, `fill="${color}"`)
		.replace(/(<svg[^>]*?)(?:\s+width="[^"]*")/g, '$1')
		.replace(/(<svg[^>]*?)(?:\s+height="[^"]*")/g, '$1')
		.replace(/(<svg)/, `$1 width="${size}" height="${size}"`);

	return (
		<span
			className="vu-icon-detail__svg"
			dangerouslySetInnerHTML={{ __html: coloredSvg }}
		/>
	);
}

export default function IconDetailModal({ icon, onClose }) {
	const { value, baseLabel, category, svgString } = icon;

	return (
		<Modal
			title={sprintf(
				// translators: %s is the icon name.
				__('Icon Preview: %s', 'vu-blocks'),
				baseLabel
			)}
			onRequestClose={onClose}
			isFullScreen
		>
			<div className="vu-icon-detail">
				{/* Header info */}
				<div className="vu-icon-detail__info">
					<dl>
						<dt>{__('Slug', 'vu-blocks')}</dt>
						<dd>
							<code>{value}</code>
						</dd>
						{category && (
							<>
								<dt>{__('Category', 'vu-blocks')}</dt>
								<dd>{category}</dd>
							</>
						)}
					</dl>
				</div>

				{/* Size showcase */}
				<section className="vu-icon-detail__sizes">
					<h3>{__('Sizes', 'vu-blocks')}</h3>
					<p className="vu-icon-detail__matrix-desc">
						{__(
							'Icon at every size on a neutral background.',
							'vu-blocks'
						)}
					</p>
					<div className="vu-icon-detail__sizes-grid">
						{ICON_SIZES.map((size) => (
							<div
								key={size}
								className="vu-icon-detail__size-cell"
							>
								<ColoredIcon
									svgString={svgString}
									color="#485259"
									size={size}
								/>
								<span className="vu-icon-detail__size-label">
									{size}px
								</span>
							</div>
						))}
					</div>
				</section>

				{/* Color matrix */}
				<section className="vu-icon-detail__matrix">
					<h3>{__('Color Matrix', 'vu-blocks')}</h3>
					<p className="vu-icon-detail__matrix-desc">
						{__(
							'Each cell shows the icon with a specific foreground color on a specific background color.',
							'vu-blocks'
						)}
					</p>

					{/* Matrix header row */}
					<div className="vu-icon-detail__matrix-table">
						<div className="vu-icon-detail__matrix-row vu-icon-detail__matrix-row--header">
							<div className="vu-icon-detail__matrix-corner">
								<span className="vu-icon-detail__matrix-axis-label">
									{__('BG ↓ / FG →', 'vu-blocks')}
								</span>
							</div>
							{ICON_COLORS.map((fg) => (
								<div
									key={fg.value}
									className="vu-icon-detail__matrix-header-cell"
								>
									<span
										className="vu-icon-detail__color-swatch"
										style={{ backgroundColor: fg.value }}
									/>
									<span>{fg.name}</span>
								</div>
							))}
						</div>

						{/* Matrix body rows */}
						{ICON_COLORS.map((bg) => (
							<div
								key={bg.value}
								className="vu-icon-detail__matrix-row"
							>
								<div className="vu-icon-detail__matrix-row-header">
									<span
										className="vu-icon-detail__color-swatch"
										style={{ backgroundColor: bg.value }}
									/>
									<span>{bg.name}</span>
								</div>
								{ICON_COLORS.map((fg) => (
									<div
										key={`${bg.value}-${fg.value}`}
										className="vu-icon-detail__matrix-cell"
										style={{
											backgroundColor: bg.value,
										}}
									>
										<ColoredIcon
											svgString={svgString}
											color={fg.value}
											size={56}
										/>
									</div>
								))}
							</div>
						))}
					</div>
				</section>

				{/* Full size × color showcase */}
				<section className="vu-icon-detail__full-showcase">
					<h3>{__('All Sizes × Colors', 'vu-blocks')}</h3>
					<p className="vu-icon-detail__matrix-desc">
						{__(
							'Icon at every size on each background color.',
							'vu-blocks'
						)}
					</p>
					{ICON_COLORS.map((bg) => {
						// Pick a contrasting foreground for readability.
						const fg = isLightColor(bg.value)
							? '#485259'
							: '#FFFFFF';

						return (
							<div
								key={bg.value}
								className={`vu-icon-detail__showcase-row vu-icon-detail__showcase-row--${bg.name
									.toLowerCase()
									.replace(/\s+/g, '-')}`}
								style={{ backgroundColor: bg.value }}
							>
								<span className="vu-icon-detail__showcase-label">
									{bg.name}
								</span>
								<div className="vu-icon-detail__showcase-icons">
									{ICON_SIZES.map((size) => (
										<ColoredIcon
											key={size}
											svgString={svgString}
											color={fg}
											size={size}
										/>
									))}
								</div>
							</div>
						);
					})}
				</section>
			</div>
		</Modal>
	);
}
