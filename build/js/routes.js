import Router from 'System/Router';
import ErrorController from "App/Controller/ErrorController";
import IndexController from "App/Controller/IndexController";
import LoginController from "App/Controller/LoginController";
import EventController from "App/Controller/EventController";

Router
    .notFound(() => {ErrorController.handleNotFound()})
    .on('/', () => {IndexController.index()})
    .on('/login', () => {LoginController.login()})
    .on('/event', () => {EventController.about()})
    .on('/event/list', () => {EventController.list()})
    .resolve();
