import React from "react";
import SmartToyIcon from "@mui/icons-material/SmartToy";
import { Fab, Popover } from "@mui/material";
import ChatWindow from "../ChatWindow/ChatWindow";

function ChatBot() {
  const [anchorEl, setAnchorEl] = React.useState(null);
  const open = Boolean(anchorEl);

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <>
      <Fab
        sx={{
          position: "fixed",
          top: "auto",
          bottom: 20,
          right: 20,
          left: "auto"
        }}
        color="primary"
        onClick={handleClick}
      >
        <SmartToyIcon />
      </Fab>
      <Popover
        open={open}
        anchorEl={anchorEl}
        onClose={handleClose}
        anchorOrigin={{
          vertical: "top",
          horizontal: "left"
        }}
        transformOrigin={{
          vertical: "bottom",
          horizontal: "right"
        }}
      >
        <ChatWindow />
      </Popover>
    </>
  );
}

export default ChatBot;
