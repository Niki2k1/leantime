<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\core\controller;
    use leantime\domain\services;

    class showAll extends controller
    {

        private $projectService;
        private $ticketService;
        private $sprintService;
        private $timesheetService;

        public function init()
        {

            $this->projectService = new services\projects();
            $this->ticketService = new services\tickets();
            $this->sprintService = new services\sprints();
            $this->timesheetService = new services\timesheets();

            $_SESSION['lastPage'] = CURRENT_URL;
            $_SESSION['lastTicketView'] = "table";
            $_SESSION['lastFilterdTicketTableView'] = CURRENT_URL;

        }

        public function get($params) {

            $currentSprint = $this->sprintService->getCurrentSprintId($_SESSION['currentProject']);

            $searchCriteria = $this->ticketService->prepareTicketSearchArray($params);

            $this->tpl->assign('allTickets', $this->ticketService->getAll($searchCriteria));
            $this->tpl->assign('allTicketStates', $this->ticketService->getStatusLabels());
            $this->tpl->assign('efforts', $this->ticketService->getEffortLabels());
            $this->tpl->assign('priorities', $this->ticketService->getPriorityLabels());
            $this->tpl->assign('types', $this->ticketService->getTicketTypes());
            $this->tpl->assign('ticketTypeIcons', $this->ticketService->getTypeIcons());

            $this->tpl->assign('searchCriteria', $searchCriteria);

            $this->tpl->assign('onTheClock', $this->timesheetService->isClocked($_SESSION["userdata"]["id"]));

            $this->tpl->assign('sprints', $this->sprintService->getAllSprints($_SESSION["currentProject"]));
            $this->tpl->assign('futureSprints', $this->sprintService->getAllFutureSprints($_SESSION["currentProject"]));

            $this->tpl->assign('users', $this->projectService->getUsersAssignedToProject($_SESSION["currentProject"]));
            $this->tpl->assign('milestones', $this->ticketService->getAllMilestones($_SESSION["currentProject"]));

            $this->tpl->assign('currentSprint', $_SESSION["currentSprint"]);
            $this->tpl->assign('sprints', $this->sprintService->getAllSprints($_SESSION["currentProject"]));

            // fields
            $this->tpl->assign('groupBy', $this->ticketService->getGroupByFieldOptions());
            $this->tpl->assign('newField', $this->ticketService->getNewFieldOptions());

            $this->tpl->display('tickets.showAll');

        }

    }

}
