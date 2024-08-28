export default async function checkUserStatus({ next, store }) {
  const isBlock = store.getters.userBlock;
  console.log(isBlock);

  if (isBlock === 1 || isBlock === undefined) {
    console.log("User is blocked");
    next('/')
  }

  next();
}
