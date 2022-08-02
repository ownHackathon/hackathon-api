import axios from "axios";

export default function useEventService() {
  function addUserAsParticipantToEvent(eventId) {
    let event = {};
    axios
        .put(`/event/participant/subscribe/${eventId}`)
        .then(async response => {
          event.value = await response.data;
        });
    return event;
  }

  function hasParticipants(participantsList)  {
    return (participantsList !== undefined && participantsList.length > 0);
  }

  function canStillParticipate(eventStatus) {
    return eventStatus < 3;
  }

  function isUserInParticipantList(participantList, user)  {
    if (hasParticipants(participantList) && user !== null) {
      return participantList.find(element => element.username === user.name) !== undefined;
    }
    return false;
  }

  return {
    addUserAsParticipantToEvent, canStillParticipate, hasParticipants, isUserInParticipantList
  };
}
