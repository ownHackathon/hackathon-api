import router from './system/router';
import index_handler from './app/handler/index_handler';

router
    .notFound(() => {index_handler.handle()})
    .on('/', () => {index_handler.handle()})
    .resolve();
