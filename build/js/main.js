import Router from 'System/Router';
import ErrorHandler from 'App/Handler/ErrorHandler';
import LoginHandler from 'App/Handler/LoginHandler';
import IndexHandler from 'App/Handler/IndexHandler';
import EventHandler from "App/Handler/EventHandler";

Router
    .notFound(() => {ErrorHandler.handleNotFound()})
    .on('/', () => {IndexHandler.handle()})
    .on('/login', () => {LoginHandler.handle()})
    .on('/event', () => {EventHandler.handleAbout()})
    .on('/event/list', () => {EventHandler.handleList()})
    .resolve();
