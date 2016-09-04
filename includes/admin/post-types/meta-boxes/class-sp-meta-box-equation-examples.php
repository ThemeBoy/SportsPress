<?php
/**
 * Equation examples meta box functions
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.19
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Equation_Examples
 */
class SP_Meta_Box_Equation_Examples {
	public static function examples( $equations = array() ) {
		?>
		<ul class="sp-equation-examples">
			<?php foreach ( $equations as $label => $equation ): $parts = explode( ' ', $equation ); ?>
				<p>
					<strong><?php echo $label; ?> =</strong>
					<?php foreach ( $parts as $part ): ?>
						<span class="button button-disabled"><?php echo $part; ?></span>
					<?php endforeach; ?>
				</p>
			<?php endforeach; ?>
		</div>
		<?php
	}
}