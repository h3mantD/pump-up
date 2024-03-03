import AddIcon from "@mui/icons-material/Add";
import { Box, Container, Fab, Grid, Typography } from "@mui/material";

function ProductDetail() {
  return (
    <Container maxWidth="xl" sx={{ py: 2 }}>
      <Grid container>
        <Grid item xs={12} sm={6}>
          <Box
            component="img"
            sx={{ height: 400, width: 400, objectFit: "contain" }}
            src="https://picsum.photos/id/1/250/250"
          />
        </Grid>
        <Grid item xs={12} sm={6}>
          <Typography variant="h5">Product Name</Typography>
          <Typography variant="body2" color="text.secondary">
            Product Description
          </Typography>
          <Typography variant="h6">Price: $100</Typography>
        </Grid>
      </Grid>
    </Container>
  );
}

export default ProductDetail;
