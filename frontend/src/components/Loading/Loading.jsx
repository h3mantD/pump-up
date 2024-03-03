import { Backdrop, CircularProgress } from "@mui/material";

function Loading() {
  return (
    <Backdrop
      open
      invisible
      sx={{ zIndex: (theme) => theme.zIndex.drawer + 1 }}
    >
      <CircularProgress color="primary" />
    </Backdrop>
  );
}

export default Loading;
