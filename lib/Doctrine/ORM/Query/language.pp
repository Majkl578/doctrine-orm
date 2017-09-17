%pragma parser.lookahead 50


%skip  space              \s


%token open_curly_brace   {
%token close_curly_brace  }
%token open_parenthesis   \(
%token close_parenthesis  \)
%token dot                \.
%token comma              ,
%token divide             /
%token multiply           \*
%token plus               \+
%token minus              \-
%token not_equals         !=
%token equals             =
%token greater_than       >=
%token lower_than         <=
%token different          <>
%token greater            >
%token lower              <
%token negate             !


// numbers
%token float              \d*\.\d+
%token integer            \d+


// booleans
%token boolean            (?i)(true|false)

// strings
%token open_apostrophe         '             -> string
%token string:char             [^']
%token string:close_apostrophe '             -> default

// parameters
%token colon              :
%token question_mark      \?


// keywords\b
%token all      (?i)\ball\b
%token and      (?i)\band\b
%token any      (?i)\bany\b
%token asc      (?i)\basc\b
%token as       (?i)\bas\b
%token avg      (?i)\bavg\b
%token between  (?i)\bbetween\b
%token both     (?i)\bboth\b
%token by       (?i)\bby\b
%token case     (?i)\bcase\b
%token coalesce (?i)\bcoalesce\b
%token count    (?i)\bcount\b
%token delete   (?i)\bdelete\b
%token desc     (?i)\bdesc\b
%token distinct (?i)\bdistinct\b
%token else     (?i)\belse\b
%token empty    (?i)\bempty\b
%token end      (?i)\bend\b
%token escape   (?i)\bescape\b
%token exists   (?i)\bexists\b
%token from     (?i)\bfrom\b
%token group    (?i)\bgroup\b
%token having   (?i)\bhaving\b
%token hidden   (?i)\bhidden\b
%token instance (?i)\binstance\b
%token index    (?i)\bindex\b
%token inner    (?i)\binner\b
%token in       (?i)\bin\b
%token is       (?i)\bis\b
%token join     (?i)\bjoin\b
%token leading  (?i)\bleading\b
%token left     (?i)\bleft\b
%token like     (?i)\blike\b
%token max      (?i)\bmax\b
%token member   (?i)\bmember\b
%token min      (?i)\bmin\b
%token new      (?i)\bnew\b
%token not      (?i)\bnot\b
%token nullif   (?i)\bnullif\b
%token null     (?i)\bnull\b
%token of       (?i)\bof\b
%token order    (?i)\border\b
%token or       (?i)\bor\b
%token outer    (?i)\bouter\b
%token partial  (?i)\bpartial\b
%token select   (?i)\bselect\b
%token set      (?i)\bset\b
%token some     (?i)\bsome\b
%token sum      (?i)\bsum\b
%token then     (?i)\bthen\b
%token trailing (?i)\btrailing\b
%token update   (?i)\bupdate\b
%token when     (?i)\bwhen\b
%token where    (?i)\bwhere\b
%token with     (?i)\bwith\b


// functions
%token function_abs               (?i)\babs\b
%token function_bit_and           (?i)\bbit_and\b
%token function_bit_or            (?i)\bbit_or\b
%token function_concat            (?i)\bconcat\b
%token function_current_date      (?i)\bcurrent_date\b
%token function_current_time      (?i)\bcurrent_time\b
%token function_current_timestamp (?i)\bcurrent_timestamp\b
%token function_date_add          (?i)\bdate_add\b
%token function_date_diff         (?i)\bdate_diff\b
%token function_date_sub          (?i)\bdate_sub\b
%token function_lower             (?i)\blower\b
%token function_length            (?i)\blength\b
%token function_locate            (?i)\blocate\b
%token function_identity          (?i)\bidentity\b
%token function_mod               (?i)\bmod\b
%token function_size              (?i)\bsize\b
%token function_sqrt              (?i)\bsqrt\b
%token function_substring         (?i)\bsubstring\b
%token function_trim              (?i)\btrim\b
%token function_upper             (?i)\bupper\b


// other terminals
%token aliased_name         (?i)[a-z_][a-z0-9_]*\:[a-z_][a-z0-9_]*(?:\\[a-z_][a-z0-9_]*)*
// TODO this currently also supports \Foo\Bar which violates EBNF, but is required by tests
%token fully_qualified_name (?i)\\?[a-z_][a-z0-9_]*(?:\\[a-z_][a-z0-9_]*)+
%token identifier           (?i)[a-z_][a-z0-9_]*


#QueryLanguage:
    SelectStatement()
    | UpdateStatement()
    | DeleteStatement()


// string compound helper rules
string:
    ::open_apostrophe:: <char>* ::close_apostrophe::
char:
    ::open_apostrophe:: <char> ::close_apostrophe::
keyword:
    <all>
    | <and>
    | <any>
    | <asc>
    | <as>
    | <avg>
    | <between>
    | <both>
    | <by>
    | <case>
    | <coalesce>
    | <count>
    | <delete>
    | <desc>
    | <distinct>
    | <else>
    | <empty>
    | <end>
    | <escape>
    | <exists>
    | <from>
    | <group>
    | <having>
    | <hidden>
    | <instance>
    | <index>
    | <inner>
    | <in>
    | <is>
    | <join>
    | <leading>
    | <left>
    | <like>
    | <max>
    | <member>
    | <min>
    | <new>
    | <not>
    | <nullif>
    | <null>
    | <of>
    | <order>
    | <or>
    | <outer>
    | <partial>
    | <select>
    | <set>
    | <some>
    | <sum>
    | <then>
    | <trailing>
    | <update>
    | <when>
    | <where>
    | <with>
function_name:
    <function_length>
    | <function_locate>
    | <function_abs>
    | <function_sqrt>
    | <function_mod>
    | <function_size>
    | <function_date_diff>
    | <function_bit_and>
    | <function_bit_or>
    | <function_current_date>
    | <function_current_time>
    | <function_current_timestamp>
    | <function_date_add>
    | <function_date_sub>
    | <function_concat>
    | <function_substring>
    | <function_trim>
    | <function_lower>
    | <function_upper>
    | <function_identity>
identifier:
    <identifier> | keyword() | function_name()


// statements
#SelectStatement:
    SelectClause() FromClause() WhereClause()? GroupByClause()? HavingClause()? OrderByClause()?
#UpdateStatement:
    UpdateClause() WhereClause()?
#DeleteStatement:
    DeleteClause() WhereClause()?


// clauses
#SelectClause:
    ::select:: <distinct>? SelectExpression() ( ::comma:: SelectExpression() )*
#SimpleSelectClause:
    ::select:: <distinct>? SimpleSelectExpression()
#UpdateClause:
    ::update:: AbstractSchemaName() ( ::as:: )? AliasIdentificationVariable() ::set:: UpdateItem() ( ::comma:: UpdateItem() )*
#DeleteClause:
    ::delete:: ::from::? AbstractSchemaName() ::as::? AliasIdentificationVariable()
#FromClause:
    ::from:: IdentificationVariableDeclaration() ( ::comma:: IdentificationVariableDeclaration() )*
#SubselectFromClause:
    ::from:: SubselectIdentificationVariableDeclaration() ( ::comma:: SubselectIdentificationVariableDeclaration() )*
#WhereClause:
    ::where:: ConditionalExpression()
#HavingClause:
    ::having:: ConditionalExpression()
#GroupByClause:
    ::group:: ::by:: GroupByItem() ( ::comma:: GroupByItem() )*
#OrderByClause:
    ::order:: ::by:: OrderByItem() ( ::comma:: OrderByItem() )*
#Subselect:
    SimpleSelectClause() SubselectFromClause() WhereClause()? GroupByClause()? HavingClause()? OrderByClause()?


// items
#UpdateItem:
    SingleValuedPathExpression() ::equals:: NewValue()
#OrderByItem:
    ( SimpleArithmeticExpression() | SingleValuedPathExpression() | ScalarExpression() | ResultVariable() | FunctionDeclaration() )
    ( <asc> | <desc> )?
#GroupByItem:
    IdentificationVariable() | ResultVariable() | SingleValuedPathExpression()

#NewValue:
// TODO EBNF specifies SimpleArithmeticExpression
    ArithmeticExpression() | <null>


// declarations
#IdentificationVariableDeclaration:
    RangeVariableDeclaration() IndexBy()? Join()*
#SubselectIdentificationVariableDeclaration:
    IdentificationVariableDeclaration()
#RangeVariableDeclaration:
    AbstractSchemaName() ::as::? AliasIdentificationVariable()
#JoinAssociationDeclaration:
    JoinAssociationPathExpression() ::as::? AliasIdentificationVariable() IndexBy()?
#Join:
    ( <left> ::outer::? | <inner> )? ::join::
    ( JoinAssociationDeclaration() | RangeVariableDeclaration() )
    ( ::with:: ConditionalExpression() )?
#IndexBy:
    ::index:: ::by:: StateFieldPathExpression()


// select expressions
#SelectExpression:
    (
        IdentificationVariable()
        | ScalarExpression()
        | AggregateExpression()
        | FunctionDeclaration()
        | PartialObjectExpression()
        | ::open_parenthesis:: Subselect() ::close_parenthesis::
        | CaseExpression()
        | NewObjectExpression()
    ) ( ::as::? <hidden>? AliasResultVariable() )?
#SimpleSelectExpression:
    (
        StateFieldPathExpression()
        | IdentificationVariable()
        | FunctionDeclaration()
        | AggregateExpression()
        | ::open_parenthesis:: Subselect() ::close_parenthesis::
        | ScalarExpression()
    ) ( ::as::? AliasResultVariable() )?
#PartialObjectExpression:
    ::partial:: IdentificationVariable() ::dot:: PartialFieldSet()
#PartialFieldSet:
    ::open_curly_brace:: SimpleStateField() ( ::comma:: SimpleStateField() )* ::close_curly_brace::
#NewObjectExpression:
    ::new:: AbstractSchemaName() ::open_parenthesis:: NewObjectArg() ( ::comma:: NewObjectArg() )* ::close_parenthesis::
#NewObjectArg:
    ScalarExpression() | ::open_parenthesis:: Subselect() ::close_parenthesis::


// conditional expressions
#ConditionalExpression:
    ConditionalTerm() ( ::or:: ConditionalTerm() )*
#ConditionalTerm:
    ConditionalFactor() ( ::and:: ConditionalFactor() )*
#ConditionalFactor:
    <not>? ConditionalPrimary()
#ConditionalPrimary:
    SimpleConditionalExpression() | ::open_parenthesis:: ConditionalExpression() ::close_parenthesis::
#SimpleConditionalExpression:
    ComparisonExpression()
    | BetweenExpression()
    | LikeExpression()
    | InExpression()
    | NullComparisonExpression()
    | ExistsExpression()
    | EmptyCollectionComparisonExpression()
    | CollectionMemberExpression()
    | InstanceOfExpression()


// collection expressions
#EmptyCollectionComparisonExpression:
    CollectionValuedPathExpression() ::is:: <not>? ::empty::
#CollectionMemberExpression:
    EntityExpression() <not>? ::member:: ::of::? CollectionValuedPathExpression()


// literals
#Literal:
    <integer> | <float> | <boolean> | char() | string()
#InParameter:
    Literal() | InputParameter()


// parameters
#InputParameter:
    PositionalParameter() | NamedParameter()
#PositionalParameter:
    ::question_mark:: <integer>
#NamedParameter:
    ::colon:: ( string() | identifier() )


// arithmetic expressions
#ArithmeticExpression:
    SimpleArithmeticExpression() | ::open_parenthesis:: Subselect() ::close_parenthesis::
#SimpleArithmeticExpression:
    ArithmeticTerm() ( ( <plus> | <minus> ) ArithmeticTerm() )*
#ArithmeticTerm:
    ArithmeticFactor() ( ( <multiply> | <divide> ) ArithmeticFactor() )*
#ArithmeticFactor:
    ( <plus> | <minus> )? ArithmeticPrimary()
#ArithmeticPrimary:
    SingleValuedPathExpression()
    | Literal()
    | ::open_parenthesis:: SimpleArithmeticExpression() ::close_parenthesis::
    | FunctionsReturningNumerics()
    | AggregateExpression()
    | FunctionsReturningStrings()
    | FunctionsReturningDateTime()
    | IdentificationVariable()
    | ResultVariable()
    | InputParameter()
    | CaseExpression()


// scalar and type expressions
#ScalarExpression:
    SimpleArithmeticExpression()
    | StringPrimary()
    | DateTimePrimary()
    | StateFieldPathExpression()
    | BooleanPrimary()
    | CaseExpression()
    | InstanceOfExpression()
#StringExpression:
    StringPrimary()
    | ResultVariable()
    | ::open_parenthesis:: Subselect() ::close_parenthesis::
#StringPrimary:
    StateFieldPathExpression()
    | string()
    | InputParameter()
    | FunctionsReturningStrings()
    | AggregateExpression()
    | CaseExpression()
#BooleanExpression:
    BooleanPrimary()
    | ::open_parenthesis:: Subselect() ::close_parenthesis::
#BooleanPrimary:
    StateFieldPathExpression()
    | <boolean>
    | InputParameter()
#EntityExpression:
    SingleValuedAssociationPathExpression()
    | SimpleEntityExpression()
#SimpleEntityExpression:
    IdentificationVariable()
    | InputParameter()
#DatetimeExpression:
    DateTimePrimary()
    | ::open_parenthesis:: Subselect() ::close_parenthesis::
#DateTimePrimary:
    StateFieldPathExpression()
    | InputParameter()
    | FunctionsReturningDateTime()
    | AggregateExpression()


// aggregate expressions
#AggregateExpression:
    ( <avg> | <max> | <min> | <sum> | <count> )
    ::open_parenthesis::
    <distinct>?
    SimpleArithmeticExpression()
    ::close_parenthesis::


// case expressions
#CaseExpression:
    GeneralCaseExpression()
    | SimpleCaseExpression()
    | CoalesceExpression()
    | NullIfExpression()
#GeneralCaseExpression:
    ::case:: WhenClause()
    WhenClause()*
    ::else:: ScalarExpression() ::end::
#WhenClause:
    ::when:: ConditionalExpression() ::then:: ScalarExpression()
#SimpleCaseExpression:
    ::case:: CaseOperand() SimpleWhenClause() SimpleWhenClause()* ::else:: ScalarExpression() ::end::
#CaseOperand:
    StateFieldPathExpression()
//  | TypeDiscriminator()
#SimpleWhenClause:
    ::when:: ScalarExpression() ::then:: ScalarExpression()
#CoalesceExpression:
    ::coalesce:: ::open_parenthesis:: ScalarExpression() ( ::comma:: ScalarExpression() )* ::close_parenthesis::
#NullIfExpression:
    ::nullif:: ::open_parenthesis:: ScalarExpression() ::comma:: ScalarExpression() ::close_parenthesis::


// other expressions
#QuantifiedExpression:
    ( <all> | <any> | <some> ) ::open_parenthesis:: Subselect() ::close_parenthesis::
#BetweenExpression:
    ArithmeticExpression() <not>? ::between:: ArithmeticExpression() ::and:: ArithmeticExpression()
#ComparisonExpression:
    ArithmeticExpression() ComparisonOperator() ( QuantifiedExpression() | ArithmeticExpression() )
#InExpression:
// TODO this differs from EBNF which only allows SingleValuedPathExpression, not ArithmeticExpression
    ArithmeticExpression()
    <not>?
    ::in::
    ::open_parenthesis::
    ( InParameter() ( ::comma:: InParameter() )* | Subselect() )
    ::close_parenthesis::
#InstanceOfExpression:
    IdentificationVariable()
    <not>?
    ::instance::
    ::of::?
    ( InstanceOfParameter() | ::open_parenthesis:: InstanceOfParameter() ( ::comma:: InstanceOfParameter() )* ::close_parenthesis:: )
#InstanceOfParameter:
    AbstractSchemaName()
    | InputParameter()
#LikeExpression:
    StringExpression() <not>? ::like:: StringPrimary() ( ::escape:: char() )?
#NullComparisonExpression:
    (
        InputParameter()
        | NullIfExpression()
        | CoalesceExpression()
        | AggregateExpression()
        | FunctionDeclaration()
        | IdentificationVariable()
        | SingleValuedPathExpression()
        | ResultVariable()
    ) ::is:: <not>? ::null::
#ExistsExpression:
    <not>? ::exists:: ::open_parenthesis:: Subselect() ::close_parenthesis::
#ComparisonOperator:
    <equals>
    | <lower>
    | <lower_than>
    | <not_equals>
    | <greater>
    | <greater_than>
    | <not_equals>
    | <different>


// identifiers
#IdentificationVariable:
    identifier()
#AliasIdentificationVariable:
    identifier()
#AbstractSchemaName:
    <fully_qualified_name>
    | <aliased_name>
    | identifier()
#AliasResultVariable:
    identifier()
#ResultVariable:
    identifier()
#FieldIdentificationVariable:
    identifier()
#CollectionValuedAssociationField:
    FieldIdentificationVariable()
#SingleValuedAssociationField:
    FieldIdentificationVariable()
#EmbeddedClassStateField:
    FieldIdentificationVariable()
#SimpleStateField:
    FieldIdentificationVariable()


// path expressions
#JoinAssociationPathExpression:
    IdentificationVariable() ::dot:: ( CollectionValuedAssociationField() | SingleValuedAssociationField() )
#AssociationPathExpression:
    CollectionValuedPathExpression() | SingleValuedAssociationPathExpression()
#SingleValuedPathExpression:
    StateFieldPathExpression() | SingleValuedAssociationPathExpression()
#StateFieldPathExpression:
    IdentificationVariable() ::dot:: StateField()
#SingleValuedAssociationPathExpression:
    IdentificationVariable() ::dot:: SingleValuedAssociationField()
#CollectionValuedPathExpression:
    IdentificationVariable() ::dot:: CollectionValuedAssociationField()
#StateField:
    ( EmbeddedClassStateField() ::dot:: )* SimpleStateField()


// functions
#FunctionDeclaration:
    FunctionsReturningStrings()
    | FunctionsReturningNumerics()
    | FunctionsReturningDateTime()


#FunctionsReturningNumerics:
    <function_length> ::open_parenthesis:: StringPrimary() ::close_parenthesis::
    | <function_locate> ::open_parenthesis:: StringPrimary() ::comma:: StringPrimary() ( ::comma:: SimpleArithmeticExpression() )? ::close_parenthesis::
    | <function_abs> ::open_parenthesis:: SimpleArithmeticExpression() ::close_parenthesis::
    | <function_sqrt> ::open_parenthesis:: SimpleArithmeticExpression() ::close_parenthesis::
    | <function_mod> ::open_parenthesis:: SimpleArithmeticExpression() ::comma:: SimpleArithmeticExpression() ::close_parenthesis::
    | <function_size> ::open_parenthesis:: CollectionValuedPathExpression() ::close_parenthesis::
    | <function_date_diff> ::open_parenthesis:: ArithmeticPrimary() ::comma:: ArithmeticPrimary() ::close_parenthesis::
    | <function_bit_and> ::open_parenthesis:: ArithmeticPrimary() ::comma:: ArithmeticPrimary() ::close_parenthesis::
    | <function_bit_or> ::open_parenthesis:: ArithmeticPrimary() ::comma:: ArithmeticPrimary() ::close_parenthesis::

#FunctionsReturningDateTime:
    <function_current_date> ::open_parenthesis:: ::close_parenthesis::
    | <function_current_time> ::open_parenthesis:: ::close_parenthesis::
    | <function_current_timestamp> ::open_parenthesis:: ::close_parenthesis::
    | <function_date_add> ::open_parenthesis:: ArithmeticPrimary() ::comma:: ArithmeticPrimary() ::comma:: StringPrimary() ::close_parenthesis::
    | <function_date_sub> ::open_parenthesis:: ArithmeticPrimary() ::comma:: ArithmeticPrimary() ::comma:: StringPrimary() ::close_parenthesis::

#FunctionsReturningStrings:
    <function_concat> ::open_parenthesis:: StringPrimary() ( ::comma:: StringPrimary() )+ ::close_parenthesis::
    | <function_substring> ::open_parenthesis:: StringPrimary() ::comma:: SimpleArithmeticExpression() ( ::comma:: SimpleArithmeticExpression() )? ::close_parenthesis::
    | <function_trim> ::open_parenthesis:: ( ( <leading> | <trailing> | <both> )? char()? ::from:: )? StringPrimary() ::close_parenthesis::
    | <function_lower> ::open_parenthesis:: StringPrimary() ::close_parenthesis::
    | <function_upper> ::open_parenthesis:: StringPrimary() ::close_parenthesis::
    | <function_identity> ::open_parenthesis:: SingleValuedAssociationPathExpression() ( ::comma:: string() )? ::close_parenthesis::
