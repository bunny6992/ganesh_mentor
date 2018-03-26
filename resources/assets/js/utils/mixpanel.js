Gym = Gym || {};

Gym.mixpanel = (function (){
    function _businessWorkoutToolsButtonClicked() {
        mixpanel.track(
            "Workout Tools Button Clicked",
            {"UserId": window.activeUser}
        );
    }

    function _ganttChartLinkOpened() {
        mixpanel.track(
            "Project Gantt Chart Opened",
            {"UserId": window.activeUser}
        );
    }

    function _resourcesLinkOpened() {
         mixpanel.track(
            "Project Resources Opened",
            {"UserId": window.activeUser}
        );
    }

    function _newModelCreated(type) {
        mixpanel.track(
            "New Model Created",
            {"UserId": window.activeUser, "Type": type}
        );
    }

    function _addNewProjectButtonClicked() {
        mixpanel.track(
            "New Project Created",
            {"UserId": window.activeUser}
        );   
    }

    return {
        businessWorkoutToolsButtonClicked: _businessWorkoutToolsButtonClicked,
        ganttChartLinkOpened: _ganttChartLinkOpened,
        resourcesLinkOpened: _resourcesLinkOpened,
        newModelCreated: _newModelCreated,
        addNewProjectButtonClicked: _addNewProjectButtonClicked
    };

}());