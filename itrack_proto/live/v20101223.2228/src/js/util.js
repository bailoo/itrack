/*
get client browser data
*/

function client_data(info)
{
	if (info == 'width')
	{
		width = (screen.width) ? screen.width:'';
		// check for windows off standard dpi screen res
		if (typeof(screen.deviceXDPI) == 'number') {
			width *= screen.deviceXDPI/screen.logicalXDPI;
		} 
		return width;
	}
	else if (info == 'height')
	{
		height = (screen.height) ? screen.height:'';
		// check for windows off standard dpi screen res
		if (typeof(screen.deviceXDPI) == 'number') {
			height *= screen.deviceYDPI/screen.logicalYDPI;
		} 
		return height;
  }
	return false;
}