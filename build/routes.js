import Router from 'build/System/Router';
import ErrorController from "build/App/Controller/ErrorController";
import IndexController from "build/App/Controller/IndexController";
import LoginController from "build/App/Controller/LoginController";
import EventController from "build/App/Controller/EventController";

Router
    .notFound(() => {ErrorController.handleNotFound()})
    .on('/', () => {IndexController.index()})
    .on('/login', () => {LoginController.login()})
    .on('/event', () => {EventController.about()})
    .on('/event/list', () => {EventController.list()})
    .resolve();
