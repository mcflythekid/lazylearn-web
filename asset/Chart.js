/**
 * @author McFly the Kid
 */
var AppChart = ((AppChart, Storage, AppApi)=>{

    var options = {
        width: '100%',
        height: 120,
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
        colors: ['#F39C12', '#66cc66' ],
        backgroundColor: '#fff',
        vAxis: {
            gridlines: {
                color: 'transparent'
            },
            textPosition: 'none'
        }
    };
    var drawUserDecks = (userId, selectorId)=>{
        draw("/user/" + userId + "/chart", selectorId);
    };
    var drawDeck = (deckId, selectorId)=>{
        draw("/deck/" + deckId + "/chart", selectorId);
    };
    var draw = (chartDataUrl, selectorId)=>{
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(()=>{
            AppApi.async.get(chartDataUrl).then((r)=>{
                var data = new google.visualization.DataTable();
                var correct = 0;
                var timeup = 0;
                $.each(r.data, (index, obj)=>{
                    correct += obj.correct;
                    timeup += obj.timeup;
                });
                data.addColumn('string','Step name');
                data.addColumn('number','Need to learn');
                //data.addColumn('number','Need to learn (' + timeup + ')');
                data.addColumn('number','Remembered');
                //data.addColumn('number','Remembered (' + correct + ')');
                data.addRows(r.data.length);
                $.each(r.data, (index, obj)=>{
                    data.setCell(index, 0, obj.stepName);
                    data.setCell(index, 1, obj.timeup);
                    data.setCell(index, 2, obj.correct);
                });
                var chart = new google.visualization.ColumnChart(document.getElementById(selectorId));
                chart.draw(data, options);
            });
        });
    };

    AppChart.drawDeck = (deckId, selectorId)=>{
        drawDeck(deckId, selectorId);
    };
    AppChart.drawCurrentUserDecks = (selectorId)=>{
        drawUserDecks(Storage.get('userData').userId, selectorId);
    };

    return AppChart;
})({}, Storage, AppApi);