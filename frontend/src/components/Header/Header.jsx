import logo from "@/assets/logo.png";
import { Box, Button, Stack } from "@mui/material";

function Header() {
  return (
    <Stack direction="row" justifyContent="space-between" sx={{ pt: 2 }}>
      <Box component="img" height={40} width={200} src={logo} />
      <Button variant="outlined" color="white" sx={{ mr: 2 }} size="small">
        Login
      </Button>
    </Stack>
  );
}

export default Header;
