import Router from './System/Router';
import ErrorHandler from "./App/Handler/ErrorHandler";
import login_handler from "./App/Handler/login_handler";
import index_handler from './App/Handler/index_handler';
import event_handler from "./App/Handler/event_handler";

Router
    .notFound(() => {ErrorHandler.handleNotFound()})
    .on('/login', () => {login_handler.handle()})
    .on('/', () => {index_handler.handle()})
    .on('/event', () => {event_handler.about()})
    .on('/event/list', () => {event_handler.list()})
    .resolve();
