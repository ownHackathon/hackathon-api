export default function useEventService() {
  const addUserAsParticipantToEvent = (userUuid, eventId) => {
    console.log(`Add ${userUuid} as Participant to EventId ${eventId}`);
  }

  return {
    addUserAsParticipantToEvent
  };
}
