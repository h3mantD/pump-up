import SmartToyIcon from "@mui/icons-material/SmartToy";
import {
  Card,
  CardContent,
  CardHeader,
  Divider,
  TextField
} from "@mui/material";

function ChatWindow() {
  return (
    <Card sx={{ width: 300 }}>
      <CardHeader avatar={<SmartToyIcon />} title="ChatBot" />
      <Divider />
      <CardContent sx={{ height: 200 }}></CardContent>
      <CardContent>
        <TextField
          size="small"
          fullWidth
          placeholder="Type your message here..."
          variant="outlined"
        />
      </CardContent>
    </Card>
  );
}

export default ChatWindow;
