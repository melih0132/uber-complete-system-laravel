using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace UBER
{
    public enum Genre
    {
        Homme,
        Femme
    }

    public class Utilisateur
    {
        private string nom;
        private string prenom;
        private DateTime dateDeNaissance;
        private Genre genre;
        private Adresse adresse;
        private string email;

        public Utilisateur(string nom, string prenom, DateTime dateDeNaissance, Genre genre, Adresse adresse, string email)
        {
            this.Nom = nom;
            this.Prenom = prenom;
            this.DateDeNaissance = dateDeNaissance;
            this.Genre = genre;
            this.Adresse = adresse;
            this.Email = email;
        }

        public string Nom
        {
            get
            {
                return this.nom;
            }

            set
            {
                this.nom = value;
            }
        }

        public string Prenom
        {
            get
            {
                return this.prenom;
            }

            set
            {
                this.prenom = value;
            }
        }

        public DateTime DateDeNaissance
        {
            get
            {
                return this.dateDeNaissance;
            }

            set
            {
                this.dateDeNaissance = value;
            }
        }

        public Genre Genre
        {
            get
            {
                return this.genre;
            }

            set
            {
                this.genre = value;
            }
        }

        public Adresse Adresse
        {
            get
            {
                return this.adresse;
            }

            set
            {
                this.adresse = value;
            }
        }

        public string Email
        {
            get
            {
                return this.email;
            }

            set
            {
                this.email = value;
            }
        }
    }

    public enum SecteurActivite
    {
        Vacances,
        Etudes,
        Epicerie,
        Restaurant
    }

    public class Entreprise
    {
        private long siret;
        private string nomSociete;
        private Adresse adresse;
        private int taille;
        private int telephone;
        private SecteurActivite secteurActivite;

        public Entreprise(long siret, string nomSociete, Adresse adresse, int taille, SecteurActivite secteurActivite)
        {
            this.Siret = siret;
            this.NomSociete = nomSociete;
            this.Adresse = adresse;
            this.Taille = taille;
            this.SecteurActivite = secteurActivite;
        }

        public Entreprise(long siret, string nomSociete, Adresse adresse, int taille, int telephone, SecteurActivite secteurActivite)
        {
            this.Siret = siret;
            this.NomSociete = nomSociete;
            this.Adresse = adresse;
            this.Taille = taille;
            this.Telephone = telephone;
            this.SecteurActivite = secteurActivite;
        }

        public long Siret
        {
            get
            {
                return this.siret;
            }

            set
            {
                this.siret = value;
            }
        }

        public string NomSociete
        {
            get
            {
                return this.nomSociete;
            }

            set
            {
                this.nomSociete = value;
            }
        }

        public Adresse Adresse
        {
            get
            {
                return this.adresse;
            }

            set
            {
                this.adresse = value;
            }
        }

        public int Taille
        {
            get
            {
                return this.taille;
            }

            set
            {
                this.taille = value;
            }
        }

        public int Telephone
        {
            get
            {
                return this.telephone;
            }

            set
            {
                this.telephone = value;
            }
        }

        public SecteurActivite SecteurActivite
        {
            get
            {
                return this.secteurActivite;
            }

            set
            {
                this.secteurActivite = value;
            }
        }
    }
}