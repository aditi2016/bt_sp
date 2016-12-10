(function () {
    'use strict';

    angular
        .module('app')
        .controller('EmployeeController', EmployeeController);

    EmployeeController.$inject = ['UserService',  'CandidateService', '$location','$routeParams', 'FlashService','$base64'];
    function EmployeeController(UserService, CandidateService,  $location, $routeParams, FlashService,$base64) {
        var vm = this;

        console.log($routeParams.emp);

        vm.user = null;
        vm.inUser = null;
        vm.date = new Date();
        vm.employeeInstance = [];
        vm.dataLoading = false;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;
        vm.threeMonths = [];
        vm.whichMonth = {};
        vm.loadUser = loadUser;
        vm.currentMonthIndex = 0;
        vm.employeeInstances = [];

        vm.markIid = 0;
        vm.toDelindex=0;
        vm.hours =0;

        vm.totalExpactedWorkingHr = 0;

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

            loadMonths();
            loadUser();

            //loadToCallCandidates();

        }
        vm.setCurrentMon = function(num,name){
            //console.log("i am in setCurrentMonth",vm.currentMonthIndex);

            vm.whichMonth.name = vm.threeMonths[vm.currentMonthIndex].name;
            vm.whichMonth.num = vm.threeMonths[vm.currentMonthIndex].num;
            console.log("i am in setCurrentMonth",vm.whichMonth);
            loadUser();

        }

        function loadMonths(){
            var months = new Array(12);
            months[0] = "January";
            months[1] = "February";
            months[2] = "March";
            months[3] = "April";
            months[4] = "May";
            months[5] = "June";
            months[6] = "July";
            months[7] = "August";
            months[8] = "September";
            months[9] = "October";
            months[10] = "November";
            months[11] = "December";

            var myDate = new Date();
            vm.whichMonth.name = months[myDate.getMonth()];
            vm.whichMonth.num = myDate.getMonth();
           vm.threeMonths[0] = {"name":months[myDate.getMonth()],"num":myDate.getMonth()};
            vm.threeMonths[1] = {"name":months[myDate.getMonth()-1], "num":myDate.getMonth()-1};
            vm.threeMonths[2] = {"name":months[myDate.getMonth()-2],"num":myDate.getMonth()-2};
            console.log(vm.threeMonths);
        }

        function loadUser(){
            vm.dataLoading = true;
            var emp = $routeParams.emp;
            vm.inUser = UserService.GetInUser();
            if(!vm.inUser.name)
                $location.path('/login');

            CandidateService.GetTodayUsage(emp)
                .then(function (response) {
                    var t = response.employee;
                    //vm.hours =
                    //file the blanks

                    var label = [];
                    var data = [];
                    for(var i=9; i< 21; i++){
                        if(i>12 )
                            label.push((i-12) + 'PM');
                        else
                            label.push(i + 'AM');
                        var obj = t.filter(function ( obj ) {
                            return obj.hour === i+'';
                        })[0];
                        //console.log(obj);

                        if(obj){
                            data.push(obj.mins);

                        } else {
                            data.push(0);
                        }
                    }
                    console.log(label,data);
                    drawTodayPerformanceBarChart(label,data);

                });

            CandidateService.GetByManagerEmployeeId(emp,(vm.whichMonth.num+1))
                .then(function (response) {
                    vm.employee = response.employee;
                    //console.log(vm.employee);
                    drawGraph();
                    vm.dataLoading = false;

                    CandidateService.GetUserLast10Instance(emp,vm.inUser.type,(vm.whichMonth.num+1))
                        .then(function (response) {

                            vm.employeeLast10Instances = response.instances;

                            for(var i = 0; i< vm.employeeLast10Instances.length;i++){
                                vm.employeeLast10Instances[i].instance = $base64.decode(vm.employeeLast10Instances[i].instance);
                                vm.employeeLast10Instances[i].program = $base64.decode(vm.employeeLast10Instances[i].program);
                            }

                            //console.log(vm.employeeInstances);

                        });

                    CandidateService.GetUserInstance(emp,vm.inUser.type,(vm.whichMonth.num+1))
                        .then(function (response) {

                            vm.employeeInstances = response.instances;

                           for(var i = 0; i< vm.employeeInstances.length;i++){
                                vm.employeeInstances[i].instance = $base64.decode(vm.employeeInstances[i].instance);
                               vm.employeeInstances[i].program = $base64.decode(vm.employeeInstances[i].program);
                            }

                            //console.log(vm.employeeInstances);

                        });

                });


        }
//polarArea
        function drawPolarAreaChart(eHrs, wHr, fHrs){

            var ctx = document.getElementById("donutchart").getContext("2d");
            var myChart = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: ["Expected Working Hrs", "Working Hrs", "Fun Hrs"],
                    datasets: [{
                        label: 'Polar Area Chart',
                        data: [eHrs, wHr, fHrs],
                        backgroundColor: [

                            "#36A2EB",
                            "#4BC0C0",
                            "#FF6384",
                        ]
                    }]
                },
                options: {
                    elements: {
                        arc: {
                            borderColor: "#000000"
                        }
                    }
                }
            });
        }

        function drawTodayPerformanceBarChart(label,data){

            var ctx = document.getElementById("todayPerformance").getContext("2d");
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [
                        {
                            label: "Working Min in Hour",

                            borderWidth: 1,
                            data: data,
                        }
                    ]
                },
                options: {
                    elements: {
                        arc: {
                            borderColor: "#000000"
                        }
                    }
                }
            });
        }

        function drawBarChart(eHrs, wHr, fHrs){

            var ctx = document.getElementById("barchart").getContext("2d");
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Expected Working Hrs", "Working Hrs", "Fun Hrs"],
                    datasets: [{
                        label: 'Performance Bar Chart',
                        data: [eHrs, wHr, fHrs],
                        backgroundColor: [

                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        }

        vm.logout = function(){
            vm.inUser = null;
            UserService.DeleteInUser();
            $location.path('#/login');
        };
        vm.decodeURIComponent = function(str){
            return decodeURIComponent(str);
        }
        vm.who = "";
        function drawGraph(){

            vm.who = vm.employee[0].name;
            var labels = [] ;
            var expectedHrs = [];
            var fun= [];
            var workingHrs = [];
            var totalWork = 0;
            var totalFun = 0;
            for(var i=0;i< vm.employee.length; i++ ){
                console.log(vm.employee[i].date);
                var t = vm.employee[i].date;
                var date = new Date(t);
                labels.push(date.getDate());
                expectedHrs.push(8);
                var dayFun =Math.floor((Math.random() * (vm.employee[i].time/60/60)) + 1);
                totalFun += dayFun;
                fun.push(dayFun);
                workingHrs.push(vm.employee[i].time/60/60);
                totalWork += vm.employee[i].time/60/60;


            };
            vm.totalExpactedWorkingHr = expectedHrs.reduce((a, b) => a + b, 0);
            vm.performance = (totalWork/vm.totalExpactedWorkingHr)*100;
            var data = {
                    labels: labels ,
            datasets: [
                {
                    label: "Expected working hours",
                    backgroundColor: "rgba(75, 192, 192,0.4)",
                    borderColor: "rgba(75, 192, 192,1)",


                    data: expectedHrs
        },
            {
                label: "Fun Time",
                    backgroundColor: "rgba(255, 99, 132,0.6)",
                borderColor: "rgba(255, 99, 132,1)",
                data: fun
            },
            {
                label: "Working Hours",
                    backgroundColor: "rgba(255, 206, 86,0.8)",
                borderColor: "rgba(255, 206, 86,1)",
                data: workingHrs
            }
        ]
        };



            var options = {

                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Hours'
                        }
                    }]
                },

                ///Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,

                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",

                //Number - Width of the grid lines
                scaleGridLineWidth: 1,

                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,

                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,

                //Boolean - Whether the line is curved between points
                bezierCurve: true,

                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.4,

                //Boolean - Whether to show a dot for each point
                pointDot: true,

                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,

                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,

                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,

                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,

                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,

                //Boolean - Whether to fill the dataset with a colour
                datasetFill: true,

                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

            };


            var ctx = document.getElementById("canvas").getContext("2d");
            new Chart(ctx, {type: 'line', data, options});

            drawBarChart(vm.totalExpactedWorkingHr,totalWork, totalFun);
            drawPolarAreaChart(vm.totalExpactedWorkingHr,totalWork, totalFun);


        }

        vm.loadToCallCandidates = loadToCallCandidates;

        vm.updateInstance = function(){
            console.log(vm.markIid);

            var instance = {'profession_id':1,'instance_id':vm.markIid,'type':'black'};

            CandidateService.UpdateInstance(instance)
                .then(function (response) {

                    vm.employeeInstances.splice( vm.toDelindex, 1 )
                    //delete vm.employeeInstances[vm.toDelindex];

                });





        }



        function loadToCallCandidates(){
            vm.search = false;
            CandidateService.GetAll()
                .then(function (response) {
                    vm.toCallCandidates = response.employees;
                    console.log(vm.toCallCandidates[1].name);
                });

        }



        function loadAllUsers() {
            UserService.GetAll()
                .then(function (users) {
                    vm.allUsers = users;
                });
        }

        function deleteUser(id) {
            UserService.Delete(id)
            .then(function () {
                loadAllUsers();
            });
        }





    }

})();