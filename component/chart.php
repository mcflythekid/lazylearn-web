<script>
    var chart = ((e)=>{
        e.drawUser = (userId, htmlId)=>{
            draw("/user/" + userId + "/chart", htmlId);
        };
        e.drawDeck = (deckId, htmlId)=>{
            draw("/deck/" + deckId + "/chart", htmlId);
        };
        var draw = (url, htmlId)=>{
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(()=>{
                $app.api.get(url).then((r)=>{
                    var data = new google.visualization.DataTable();
                    data.addColumn('string','Step name');
                    data.addColumn('number','Correct');
                    data.addColumn('number','Timeup');
                    data.addRows(r.data.length);
                    $.each(r.data, (index, obj)=>{
                        data.setCell(index, 0, obj.stepName);
                        data.setCell(index, 1, obj.correct);
                        data.setCell(index, 2, obj.timeup);
                    });
                    var chart = new google.visualization.ColumnChart(document.getElementById(htmlId));
                    chart.draw(data, options);
                });
            });
        }
        var options = {
            width: '100%',
            height: 200,
            legend: { position: 'top', maxLines: 3 },
            bar: { groupWidth: '75%' },
            isStacked: true,
            colors: ['#66cc66',  '#fad163'],
        };
        return e;
    })({});
</script>