import { Box, Container, Grid, Typography } from "@mui/material";

function Footer() {
  return (
    <Container maxWidth="xl" sx={{ background: "black" }}>
      <Box
        display="flex"
        alignItems="center"
        justifyContent="center"
        height={50}
      >
        <Typography variant="h6" color="whitesmoke" textAlign="center">
          Copyright © 2024 Pump up
        </Typography>
      </Box>
    </Container>
  );
}

export default Footer;
