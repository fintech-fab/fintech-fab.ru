<?php
/**
 * @var array $aParams
 */

?>

<!-- КРЕДДИ tracking code start -->
<script type="text/javascript">
	(function () {
		var zmc = document.createElement('script');
		zmc.type = 'text/javascript';
		zmc.async = true;
		zmc.src = '//t.zm-trk.com/track.js?p=1875&order_id=<?= CHtml::encode($aParams['iOrderId']) ?>';
		var z1 = document.getElementsByTagName('script')[0];
		z1.parentNode.insertBefore(zmc, z1);
	})();
</script>

<!-- КРЕДДИ tracking code end -->

