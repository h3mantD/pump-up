import logo from "@/assets/logo.png";
import { AppBar, Box, Button, Stack } from "@mui/material";

function Header() {
  return (
    <AppBar position="static" sx={{ background: "#21211D" }} elevation={0}>
      <Stack direction="row" justifyContent="space-between" sx={{ py: 2 }}>
        <Box component="img" height={40} width={200} src={logo} />
        <Button variant="outlined" color="white" sx={{ mr: 2 }} size="small">
          Login
        </Button>
      </Stack>
    </AppBar>
  );
}

export default Header;
