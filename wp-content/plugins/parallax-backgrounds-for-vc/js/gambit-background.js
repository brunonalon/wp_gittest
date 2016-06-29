document.addEventListener('DOMContentLoaded', function() {

	var elements = document.querySelectorAll('.gambit_background_row');

	// Set up the hover div
	Array.prototype.forEach.call(elements, function(el, i) {
		var row = document.gambitFindElementParentRow( el );

		var styles = getComputedStyle( el );
		row.style.backgroundImage = styles.backgroundImage;
		row.style.backgroundColor = styles.backgroundColor;
		row.style.backgroundRepeat = styles.backgroundRepeat;
		row.style.backgroundSize = styles.backgroundSize;
		row.style.backgroundPosition = styles.backgroundPosition;
		
	});
	
});