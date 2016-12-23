/**
 * Created by Daniel on 04.01.2016.
 */

var toolsVersions = function(){

    var dataSource = "";
    var dataTarget = "/ajax";

    return {

        /**************************** Advertisers ***********************/

        loadToolsVersions: function(selectedStatus,outputElement){
            var paramsArr = {};

            paramsArr.status = (typeof(selectedStatus) != 'undefined'? selectedStatus : "all");
            paramsArr.id = "";
           // paramsArr.country = $("select[name=country]").val();

            var jsnStr = JSON.stringify(paramsArr);
           // console.log(jsnStr);
            $.ajax({
                global : true,
                url : dataTarget,
                data : {
                    controller : 'ToolsVersionLog',
                    method : 'loadToolsVersionIndex',
                    params : jsnStr,
                    id : paramsArr.id,
                },
                dataType : 'json',
                type : 'POST',
                success : function(response){
                    // right now the reload is commented out because the banner code wont be seen/usable on page reload
                    // TODO : find better logic
                    // window.location.reload(true)
                    // collageAd.loadAdvertiserProductDataColumns("#productDataColumns");

                    if(response.items.length >0){
                        $(outputElement).html(response.items);
                        //console.log(response.items);

                        $('#toolsVersionsList').dataTable( {
                            "destroy": true,
                            "bProcessing": true,
                            // "bServerSide": true,
                            "deferRender": true,
                            "sPaginationType": "bootstrap",
                            "iDisplayLength": 50,
                            "initComplete": function() {
                                //listSlide(".lineItemList",'#activeCampaignList');
                                //listSlide(".apiNetworkList",'#activeCampaignList');
                            }
                        });

                    }
                    // ajaxLoader("stop","");
                    // right now the modal is closed when the save is done
                    //$('#ajaxLoaderWaitWrap').modal('hide');
                }
            });
        },


    }
}();


/**************************** DOCUMENT ON CHANGE *******************************/

$(document).on({
    change: function(){
        var status = $("select[name=status]").val();
        toolsVersions.loadToolsVersions(status,"#adTypesListContainer");

    }
},"select[name=adTypeStatus]");


/****************************** DOCUMENT READY ********************************/

$(document).ready(function() {

    /************************ AdTypes *************************/

    if($("#toolsVersionListContainer").length != 0){
        toolsVersions.loadToolsVersions("all","#toolsVersionListContainer");
    }//countriesList

    if($('#toolsVersionForm').length != 0){

        $('input[name=publish_start]').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "startDate" : $('input[name=publish_start]').val(),
            "opens": "center",
            "locale": {
                format: 'YYYY-MM-DD HH:mm',
            },
        }, function(start, end, label) {
            //console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        });

        $('#publishEnd').daterangepicker({
            "singleDatePicker": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "startDate" : $('input[name=publish_end]').val(),
            "opens": "center",
            "locale": {
                format: 'YYYY-MM-DD HH:mm',
            }
        }, function(start, end, label) {
            //console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        });


        if($("#versionContentShort").length != 0){
            CKEDITOR.replace('versionContentShort');
        }

        if($("#versionContent").length != 0){
            CKEDITOR.replace('versionContent');
        }

        CKEDITOR.editorConfig = function( config ) {
            config.language = 'de';
            config.uiColor = '#F7B42C';
            config.height = 300;
            config.toolbarCanCollapse = true;
        };


    }//toolsForm edit/create



});