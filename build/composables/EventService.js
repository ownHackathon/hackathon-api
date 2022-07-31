export default function useEventService() {
  function addUserAsParticipantToEvent(userUuid, eventId) {
    /** TODO addUserAsParticipantToEvent */
    console.log(`Add ${userUuid} as Participant to EventId ${eventId}`);
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
