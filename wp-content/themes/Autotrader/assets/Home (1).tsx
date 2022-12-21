import React, { ChangeEvent } from "react"
import { Container, Grid, InputAdornment, TextField, Typography, Box, Button, IconButton, Chip } from "@mui/material"
import PokemonCard from "../components/PokemonCard"
import { Field, PokemonType, usePokemonContext } from "../components/Contexts/PokemonProvider"
import { Search, FavoriteBorder, Favorite, Close } from "@mui/icons-material"
import PokemonTypeIcon from "../components/PokemonTypeIcon"
import { getTheme } from "../theme/index"

const Home: React.FC = () => {
  const {
    pokemon,
    query,
    search,
    favourites,
    addFavourite,
    removeFavourite,
    filters,
    addFilter,
    removeFilter,
    togglePokemonType,
    selectedPokemonTypes
  } = usePokemonContext()

  function handleQueryChange(event: ChangeEvent<HTMLInputElement>) {
    search(event.target.value)
  }

  const handleToggleFavourites = () => {
    if (filters[Field.favourite]) {
      removeFilter(Field.favourite)
    } else {
      addFilter(Field.favourite, true)
    }
  }

  return (
    <Container maxWidth="lg" sx={{ py: 2 }}>
      <Typography variant="h1">What Pokemon <br/>are you looking for?</Typography>
      <Box
        sx={{
          display: "flex",
          pt: 4,
          pb: 2
        }}
      >
        <TextField
          id="pokemon-search"
          placeholder="Search Pokemon"
          variant="outlined"
          value={query}
          onChange={handleQueryChange}
          InputProps={{
            sx: { pr: 0 },
            startAdornment: <InputAdornment position="start"><Search /></InputAdornment>,
            endAdornment: <InputAdornment position="end">
              <IconButton onClick={() => search("")}><Close /></IconButton>
            </InputAdornment>
          }}
        />

        <Button
          startIcon={filters[Field.favourite]
            ? <Favorite />
            : <FavoriteBorder />
          }
          color={filters[Field.favourite] ? "primary" : "secondary"}
          sx={{
            flexShrink: 0,
            ml: "2rem"
          }}
          onClick={handleToggleFavourites}
        >
          My Favourites ({favourites.length})
        </Button>
      </Box>
      <Box sx={{ mb: "2rem" }}>
        {
          Object.values(PokemonType).map((pokemonType) => {
            const isSelected = selectedPokemonTypes.includes(pokemonType)
            const theme = getTheme(pokemonType)

            return <Chip
              key={pokemonType}
              label={pokemonType}
              icon={<PokemonTypeIcon type={pokemonType} />}
              clickable
              sx={{ backgroundColor: isSelected ? theme.palette.primary.main : "inherit",
                color: isSelected ? "none" : "inherit", m: "0.5rem" }}
              color={isSelected ? "primary" : "default"}
              onClick={() => togglePokemonType(pokemonType)} />
          })
        }
      </Box>

      <Grid container spacing={2}>
        {pokemon.map((pokemon) => (
          <Grid
            item
            xs={12}
            sm={6}
            md={4}
            key={pokemon.name}
          >
            <PokemonCard
              pokemon={pokemon}
              isFavourite={favourites.includes(pokemon.name)}
              onAddFavourite={() => addFavourite(pokemon)}
              onRemoveFavourite={() => removeFavourite(pokemon)}
            />
          </Grid>
        ))}
      </Grid>
    </Container>
  )
}

export default Home