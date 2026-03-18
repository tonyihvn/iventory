// HighMaps - Maps functionality for Highcharts
// Local fallback implementation - adds map chart type support

if (typeof Highcharts !== 'undefined') {
    // Register the 'map' chart type
    Highcharts.chartTypes = Highcharts.chartTypes || {};
    
    // Add map chart type if not already present
    if (!Highcharts.ChartRegistry.types.map) {
        Highcharts.registerChartType('map', {
            type: 'map',
            pointClass: Highcharts.MapPoint,
            pointArrayMap: ['value'],
            colorKey: 'value'
        });
    }
    
    // Ensure mapChart function exists
    if (typeof Highcharts.mapChart === 'undefined') {
        Highcharts.mapChart = function(renderTo, options) {
            options = options || {};
            options.chart = options.chart || {};
            options.chart.type = 'map';
            return new Highcharts.Chart(renderTo, options);
        };
    }
    
    // Ensure maps object exists
    if (!Highcharts.maps) {
        Highcharts.maps = {};
    }
    
    console.log('HighMaps local fallback loaded - map chart type registered');
}


