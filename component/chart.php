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
                    var chart = new google.visualization.ColumnChart(document.getElementById(htmlId));
                    chart.draw(data, options);
                });
            });
        }
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
        return e;
    })({});
</script>