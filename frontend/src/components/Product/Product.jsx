import Card from "@mui/material/Card";
import CardContent from "@mui/material/CardContent";
import CardMedia from "@mui/material/CardMedia";
import Typography from "@mui/material/Typography";
import { CardActionArea } from "@mui/material";

function Product({ item }) {
  return (
    <Card>
      <CardActionArea>
        <CardMedia
          component="img"
          height="250"
          image={`https://picsum.photos/id/${item.id}/250/250`}
          alt={item.name}
        />
        <CardContent>
          <Typography gutterBottom variant="h5" component="div">
            {item.name}
          </Typography>
          <Typography
            variant="body2"
            color="text.secondary"
            noWrap
            title={item.description}
          >
            {item.description}
          </Typography>
        </CardContent>
      </CardActionArea>
    </Card>
  );
}

export default Product;
