import logo from "@/assets/logo.png";
import { AppBar, Box, Stack } from "@mui/material";

function Header() {
  return (
    <AppBar position="static" sx={{ background: "#21211D" }} elevation={0}>
      <Stack direction="row" justifyContent="space-between" sx={{ py: 2 }}>
        <Box component="img" height={40} width={200} src={logo} />
      </Stack>
    </AppBar>
  );
}

export default Header;
