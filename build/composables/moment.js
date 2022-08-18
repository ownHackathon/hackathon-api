import moment from "moment";

function dateTime(value) {
  return moment(value).format("DD.MM.YYYY - HH:mm");
}

function databaseDateTime(value) {
  return moment(value).format("YYYY-MM-DD HH:mm:ss");
}

function date(value) {
  return moment(value).format("DD.MM.YYYY");
}

function addTime(value, count) {
  return moment(value,).add(count, 'days');

}

export {
  date, dateTime, databaseDateTime, addTime,
};
