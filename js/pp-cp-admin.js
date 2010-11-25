jQuery(document).ready(function($) {
	
		$(".updated").fadeIn("slow");
		
		$("#cp_win_pts_button").click(function () {
			$("#cp_win_pts").attr( "disabled", function() {
				return !this.disabled;
			});
	    });
		$("#cp_sell_pts_button").click(function () {
			$("#cp_sell_pts").attr( "disabled", function() {
				return !this.disabled;
			});
	    });
		$("#cp_bid_pts_button").click(function () {
			$("#cp_bid_pts").attr( "disabled", function() {
				return !this.disabled;
			});
	    });
});