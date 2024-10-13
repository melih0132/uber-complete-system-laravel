namespace UBER
{
    public class Adresse
    {
        private string rue;
        private List<Ville> villes;
        private CodePostal cp;

        public Adresse(string rue, List<Ville> villes, CodePostal cp)
        {
            this.Rue = rue;
            this.Villes = villes;
            this.Cp = cp;
        }

        public Adresse(List<Ville> villes, CodePostal cp)
        {
            this.Villes = villes;
            this.Cp = cp;
        }

        public Adresse(string rue, CodePostal cp)
        {
            this.Rue = rue;
            this.Villes = new List<Ville>();
            this.Cp = cp;
        }

        public string Rue
        {
            get
            {
                return this.rue;
            }

            set
            {
                this.rue = value;
            }
        }

        public List<Ville> Villes
        {
            get
            {
                return this.villes;
            }

            set
            {
                this.villes = value;
            }
        }

        public CodePostal Cp
        {
            get
            {
                return this.cp;
            }

            set
            {
                this.cp = value;
            }
        }
    }

    public class Ville
    {
        private string nom;
        private CodePostal codePostal;

        public Ville(string nom, CodePostal codePostal)
        {
            this.Nom = nom;
            this.CodePostal = codePostal;
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

        public CodePostal CodePostal
        {
            get
            {
                return this.codePostal;
            }

            set
            {
                this.codePostal = value;
            }
        }
    }

    public class CodePostal
    {
        private int cp;
        private List<Ville> lesVilles;

        public CodePostal(int cp)
        {
            this.Cp = cp;
            this.LesVilles = new List<Ville>();
        }

        public CodePostal(int cp, List<Ville> lesVilles)
        {
            this.Cp = cp;
            this.LesVilles = lesVilles;
        }

        public int Cp
        {
            get
            {
                return this.cp;
            }

            set
            {
                this.cp = value;
            }
        }

        public List<Ville> LesVilles
        {
            get
            {
                return this.lesVilles;
            }

            set
            {
                this.lesVilles = value;
            }
        }
    }
}