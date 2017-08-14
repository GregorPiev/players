'use strict';

class viewData {
    constructor() {
        this.data;
        this.dataStoreSave = [];
        this.dataPlayers = ['<option value="-1">Choose Player</option>'];
        this.dataBrands = ['<option value="-1">Choose Brand</option>'];

    }

    setData(dataVal) {
        this.data = dataVal;
        let _this = this;

        this.data.map(function (ind, item) {
            console.info("%cPlayer market:" + $(item).attr('market'), "color: violet");
            let iter = ind + 1;
            let newPlayerItem = "<option value='" + iter + "'>" + iter + "</option>";
            _this.dataPlayers.push(newPlayerItem);


            _this.dataStoreSave[ind] = {};
            _this.dataStoreSave[ind].playerId = iter;
            _this.dataStoreSave[ind].day = $(item).attr('day');
            _this.dataStoreSave[ind].market = $(item).attr('market');
            _this.dataStoreSave[ind].affiliateId = $(item).attr('affiliateId');
            _this.dataStoreSave[ind].campaign = $(item).attr('campaign');
            _this.dataStoreSave[ind].acquisitionNumber = $(item).attr('acquisitionNumber');
            _this.dataStoreSave[ind].brand = $(item).attr('brand');
            _this.dataStoreSave[ind].device = $(item).attr('device');
            _this.dataStoreSave[ind].currency = $(item).attr('currency');
            _this.dataStoreSave[ind].firstPlayed = $(item).attr('firstPlayed');
            _this.dataStoreSave[ind].lastPlayed = $(item).attr('lastPlayed');
            _this.dataStoreSave[ind].numberOfLifetimeDeposits = $(item).attr('numberOfLifetimeDeposits');
            _this.dataStoreSave[ind].lifetimeDeposits = $(item).attr('lifetimeDeposits');
            _this.dataStoreSave[ind].firstDepositAmount = $(item).attr('firstDepositAmount');
            _this.dataStoreSave[ind].isPlayerLocked = $(item).attr('isPlayerLocked');
            _this.dataStoreSave[ind].fraudLocked = $(item).attr('fraudLocked');
            _this.dataStoreSave[ind].negativeBrand = $(item).attr('negativeBrand');
            _this.dataStoreSave[ind].highRollerAdjusted = $(item).attr('highRollerAdjusted');
            _this.dataStoreSave[ind].netRevenue = $(item).attr('netRevenue');
            _this.dataStoreSave[ind].earnings = $(item).attr('earnings');
            _this.dataStoreSave[ind].media = $(item).attr('media');
            _this.dataStoreSave[ind].withdrawals = $(item).attr('withdrawals');
        });

        this.initDataTable();
        this.insertData();
        this.setSelectPlyer();
    }

    setSelectPlyer() {
        let playerSelect = this.dataPlayers.join('');
        $("#player").append(playerSelect);
    }

    setSelectBranch() {
        let  _this = this;
        var postdata = {
            op: 'initbrand'
        };
        $.ajax({
            url: "http://gregportfolio.info/players/php/api.php",
            method: 'POST',
            data: JSON.stringify(postdata),
            success: function (resp) {
                console.log("%cSuccess Brand:" + JSON.stringify(resp), "color:blue");
                $.each(resp.data, function (ind, val) {
                    let newBrandItem = "<option value='" + val.brand + "'>" + val.brand + "</option>";
                    _this.dataBrands.push(newBrandItem);
                });
                let brandSelect = _this.dataBrands.join('');
                $("#brand").append(brandSelect);
            },
            error: function (error) {
                console.log("%cError insertData:" + JSON.stringify(error), "color:#f00");
            }

        });


    }

    insertData() {
        let sendArray = this.dataStoreSave;
        let dataArray = Object.assign({}, sendArray);
        let _this = this;
        var postdata = {
            op: 'firstinsert',
            data: dataArray
        };
        $.ajax({
            url: "http://gregportfolio.info/players/php/api.php",
            method: 'POST',
            data: JSON.stringify(postdata),
            success: function (resp) {
                console.log("%cSuccess insertData:" + JSON.stringify(resp), "color:blue");
                _this.setSelectBranch();
            },
            error: function (error) {
                console.log("%cError insertData:" + JSON.stringify(error), "color:#f00");
            }

        });
    }

    initDataTable() {
        $('#dataStore').dataTable({

            "sDom": 'T<"clear">lfrtip',
            "bDestroy": true,
            "bLengthChange": true,
            "bAutoWidth": true,
            "aaData": this.dataStoreSave,
            "aaSorting": [[0, "asc"]],
            "aoColumns": [
                {"mData": "playerId", "sTitle": "Player Id"},
                {"mData": "day", "sTitle": "Day", "sClass": "columnX center"},
                {"mData": "market", "sTitle": "Market", "sClass": "columnX center"},

                {"mData": "affiliateId", "sTitle": "AffiliateId", "sClass": "columnX center"},
                {"mData": "campaign", "sTitle": "Campaign", "sClass": "columnX center"},
                {"mData": "acquisitionNumber", "sTitle": "Acquisition Number", "sClass": "columnX center"},

                {"mData": "brand", "sTitle": "Brand", "sClass": "columnX center"},
                {"mData": "device", "sTitle": "Device", "sClass": "columnX center"},
                {"mData": "currency", "sTitle": "Currency", "sClass": "columnX center"},

                {"mData": "firstPlayed", "sTitle": "First Played", "sClass": "columnX center"},
                {"mData": "lastPlayed", "sTitle": "Last Played", "sClass": "columnX center"},
                {"mData": "numberOfLifetimeDeposits", "sTitle": "Number Of Lifetime Deposits", "sClass": "columnX center"},

                {"mData": "lifetimeDeposits", "sTitle": "Life time Deposits", "sClass": "columnX center"},
                {"mData": "firstDepositAmount", "sTitle": "First Deposit Amount", "sClass": "columnX center"},
                {"mData": "isPlayerLocked", "sTitle": "Is Player Locked", "sClass": "columnX center"},

                {"mData": "fraudLocked", "sTitle": "Fraud Locked", "sClass": "columnX center"},
                {"mData": "negativeBrand", "sTitle": "Negative Brand", "sClass": "columnX center"},
                {"mData": "highRollerAdjusted", "sTitle": "High Roller Adjusted", "sClass": "columnX center"},

                {"mData": "netRevenue", "sTitle": "Net Revenue", "sClass": "columnX center"},
                {"mData": "earnings", "sTitle": "Earnings", "sClass": "columnX center"},
                {"mData": "media", "sTitle": "Media", "sClass": "columnX center"},
                {"mData": "withdrawals", "sTitle": "Withdrawals", "sClass": "columnX center"}
            ],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var totalDeposit = 0;
                var totalRevenue = 0;

                for (var i = 0; i < aiDisplay.length; i++) {
                    totalDeposit = +aaData[i].earnings;
                    totalRevenue = +aaData[i].netRevenue;
                }

                $("#totalDeposit").find('span').html(totalDeposit);
                $("#totalRevenue").find('span').html(totalRevenue);
            }
        });
    }

    choosedBrand(brand) {
        let _this = this;
        var postdata = {
            op: 'newbrand',
            data: brand
        };
        $.ajax({
            url: "http://gregportfolio.info/players/php/api.php",
            method: 'POST',
            data: JSON.stringify(postdata),
            success: function (resp) {
                console.log("%cSuccess Choose Brand:" + JSON.stringify(resp), "color:blue");
                _this.dataStoreSave = resp.data;
                _this.initDataTable();
            },
            error: function (error) {
                console.log("%cError insertData:" + JSON.stringify(error), "color:#f00");
            }

        });
    }
    choosedPlayer(player) {
        let _this = this;
        var postdata = {
            op: 'newplayer',
            data: player
        };
        $.ajax({
            url: "http://gregportfolio.info/players/php/api.php",
            method: 'POST',
            data: JSON.stringify(postdata),
            success: function (resp) {
                console.log("%cSuccess Choose Player:" + JSON.stringify(resp), "color:blue");
                _this.dataStoreSave = resp.data;
                _this.initDataTable();
            },
            error: function (error) {
                console.log("%cError Choose Player:" + JSON.stringify(error), "color:#f00");
            }

        });
    }

}

$(document).ready(function () {
    let vwDat = new viewData();

    $.ajax({
        url: "http://gregportfolio.info/players/source/players.xml",
        method: 'GET',
        dataType: "xml",
        success: function (xml) {
            console.log("XML:" + xml);
            var player = $(xml).find('player');
            console.log("player:" + player);
            vwDat.setData(player);
        },
        error: function (err) {
            console.info("%cError Message:" + err, "color:red;");
        }

    });

    $("#brand").on('change', function () {
        if ($(this).val() === -1) {
            alert("Plese, chose value");
        } else {
            vwDat.choosedBrand($(this).val());
        }
    });
    $("#player").on('change', function () {
        if ($(this).val() === -1) {
            alert("Plese, chose value");
        } else {
            vwDat.choosedPlayer($(this).val());
        }
    });
});


