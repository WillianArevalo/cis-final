<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for("admin", function (BreadcrumbTrail $trail) {
    $trail->push("Admin", route("admin.dashboard"), ["icon" => "layout-dashboard"]);
});

Breadcrumbs::for("admin.dashboard", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Panel de control", route("admin.dashboard"));
});

Breadcrumbs::for("admin.users.index", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Usuarios", route("admin.users.index"));
});

Breadcrumbs::for("admin.communities.index", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Comunidades", route("admin.communities.index"));
});

Breadcrumbs::for("admin.projects.index", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Proyectos", route("admin.projects.index"));
});

Breadcrumbs::for("admin.projects.create", function (BreadcrumbTrail $trail) {
    $trail->parent("admin.projects.index");
    $trail->push("Crear proyecto", route("admin.projects.create"));
});

Breadcrumbs::for("admin.projects.edit", function (BreadcrumbTrail $trail, int $projectId) {
    $trail->parent("admin.projects.index");
    $trail->push("Editar proyecto", route("admin.projects.edit", $projectId));
});

Breadcrumbs::for("admin.projects.show", function (BreadcrumbTrail $trail, int $projectId) {
    $trail->parent("admin.projects.index");
    $trail->push("Detalle del proyecto", route("admin.projects.show", $projectId));
});

Breadcrumbs::for("admin.scholars.index", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Becados", route("admin.scholars.index"));
});

Breadcrumbs::for("admin.reports.index", function (BreadcrumbTrail $trail, ?int $projectId = null) {
    $trail->parent("admin");
    $trail->push("Proyectos", route("admin.projects.index"));
    if ($projectId) {
        $trail->push("Reportes del proyecto", route("admin.reports.index", $projectId));
    } else {
        $trail->push("Reportes", route("admin.reports.index"));
    }
});

Breadcrumbs::for("admin.reports.show", function (BreadcrumbTrail $trail, int $reportId) {
    $trail->parent("admin.reports.index");
    $trail->push("Detalle del reporte", route("admin.reports.show", $reportId));
});

Breadcrumbs::for("admin.profile", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Perfil", route("admin.profile"));
});

Breadcrumbs::for("admin.settings", function (BreadcrumbTrail $trail) {
    $trail->parent("admin");
    $trail->push("Configuración", route("admin.settings"));
});
